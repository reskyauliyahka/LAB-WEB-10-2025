// Menunggu hingga seluruh dokumen HTML selesai dimuat sebelum menjalankan script.
// Ini adalah praktik terbaik untuk memastikan semua elemen HTML (seperti tombol dan div) sudah ada dan siap dimanipulasi.
document.addEventListener('DOMContentLoaded', () => {
    // =================================================================================
    // BAGIAN 1: SETUP DAN KONFIGURASI
    // Kumpulan semua variabel dan state awal permainan.
    // =================================================================================

    // Objek 'uiElements' berfungsi sebagai "cache" untuk semua elemen DOM (HTML) yang akan kita gunakan.
    // Mengumpulkannya di satu tempat membuat kode lebih rapi dan lebih cepat karena tidak perlu berulang kali memanggil document.getElementById().
    const uiElements = {
        backgroundMedia: document.getElementById('background-media'),
        gameBackgroundMedia: document.getElementById('game-background-media'),
        gameContainer: document.getElementById('game-container'),
        playerHand: document.getElementById('player-hand'),
        botHand: document.getElementById('bot-hand'),
        discardPile: document.getElementById('discard-pile'),
        drawPile: document.getElementById('draw-pile'),
        unoButton: document.getElementById('uno-button'),
        callUnoOnBotButton: document.getElementById('call-uno-on-bot-button'),
        turnIndicator: document.getElementById('turn-indicator'),
        balance: document.getElementById('balance'),
        currentBet: document.getElementById('current-bet'),
        startModal: document.getElementById('start-modal'),
        colorPickerModal: document.getElementById('color-picker-modal'),
        gameOverModal: document.getElementById('game-over-modal'),
        modalBalance: document.getElementById('modal-balance'),
        betInput: document.getElementById('bet-input'),
        startGameButton: document.getElementById('start-game-button'),
        restartGameButton: document.getElementById('restart-game-button'),
    };

    // Objek 'gameState' adalah "sumber kebenaran tunggal" (single source of truth).
    // Semua data permainan seperti kartu di tangan, giliran siapa, saldo, dll., disimpan di sini.
    // Memisahkan data dari tampilan (HTML) adalah konsep penting dalam pemrograman.
    const gameState = {
        deck: [],
        playerHand: [],
        botHand: [],
        discardPile: [],
        currentPlayer: 'player', // Bisa 'player' atau 'bot'
        balance: 5000,
        currentBet: 0,
        playerUnoTimer: null,      // Timer untuk memeriksa apakah pemain lupa menekan UNO
        isPlayerUnoCalled: false,  // Status apakah pemain sudah menekan UNO
        botHasCalledUno: false,    // Status apakah bot sudah "memanggil" UNO
        playerCallUnoTimer: null,  // Timer untuk kesempatan pemain menangkap bot yang lupa UNO
    };

    // =================================================================================
    // BAGIAN 2: FUNGSI UTAMA PENGELOLA TAMPILAN (VIEW)
    // Fungsi ini bertanggung jawab untuk mengubah tampilan layar (misal: dari menu ke game).
    // =================================================================================

    /**
     * Mengatur elemen mana yang terlihat di layar berdasarkan state permainan.
     * @param {string} viewName - Nama tampilan yang ingin diaktifkan ('startScreen', 'gameScreen', 'gameOverScreen', 'colorPicker').
     */
    function updateView(viewName) {
        // Langkah 1: Sembunyikan semua elemen utama terlebih dahulu untuk "mereset" tampilan.
        uiElements.backgroundMedia.classList.add('hidden');
        uiElements.gameBackgroundMedia.classList.add('hidden');
        uiElements.gameContainer.classList.add('opacity-0'); // Menggunakan opacity untuk efek transisi
        uiElements.startModal.classList.add('hidden');
        uiElements.gameOverModal.classList.add('hidden');
        uiElements.colorPickerModal.classList.add('hidden');

        // Langkah 2: Gunakan switch-case untuk menampilkan elemen yang sesuai dengan viewName.
        switch (viewName) {
            case 'startScreen':
                uiElements.backgroundMedia.classList.remove('hidden');
                uiElements.startModal.classList.remove('hidden');
                break;
            case 'gameScreen':
                uiElements.gameBackgroundMedia.classList.remove('hidden');
                uiElements.gameContainer.classList.remove('opacity-0');
                break;
            case 'gameOverScreen':
                uiElements.gameBackgroundMedia.classList.remove('hidden');
                uiElements.gameContainer.classList.remove('opacity-0');
                uiElements.gameOverModal.classList.remove('hidden');
                break;
            case 'colorPicker':
                // Tampilan ini mirip dengan gameScreen, tapi dengan tambahan modal pemilih warna.
                uiElements.gameBackgroundMedia.classList.remove('hidden');
                uiElements.gameContainer.classList.remove('opacity-0');
                uiElements.colorPickerModal.classList.remove('hidden');
                break;
        }
    }

    // =================================================================================
    // BAGIAN 3: FUNGSI-FUNGSI LOGIKA INTI PERMAINAN
    // Semua fungsi yang berhubungan dengan aturan dan alur permainan UNO.
    // =================================================================================

    /**
     * Membuat satu set tumpukan kartu UNO (108 kartu) secara terprogram.
     * @returns {Array} - Array of card objects.
     */
    function createDeck() {
        const colors = ['red', 'green', 'blue', 'yellow'];
        const values = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'skip', 'reverse', 'drawTwo'];
        const wildCards = ['wild', 'wildDrawFour'];
        let newDeck = [];

        // Membuat kartu berwarna (angka dan aksi)
        for (const color of colors) {
            for (const value of values) {
                newDeck.push({ color, value });
                // Kartu 1-9, skip, reverse, drawTwo ada dua di setiap warna. Kartu 0 hanya satu.
                if (value !== '0') newDeck.push({ color, value });
            }
        }

        // Membuat kartu Wild (4 Wild, 4 Wild Draw Four)
        for (let i = 0; i < 4; i++) {
            for (const wild of wildCards) {
                newDeck.push({ color: 'black', value: wild }); // Warna 'black' untuk identifikasi kartu Wild
            }
        }
        return newDeck;
    }

    /**
     * Mengacak urutan kartu dalam sebuah tumpukan menggunakan algoritma Fisher-Yates.
     * @param {Array} deck - Tumpukan kartu yang akan diacak.
     */
    function shuffle(deck) {
        for (let i = deck.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            // Tukar posisi elemen i dan j dengan sintaks modern (destructuring assignment)
            [deck[i], deck[j]] = [deck[j], deck[i]];
        }
    }
    
    /**
     * Memulai ronde permainan baru.
     */
    function startGame() {
        // Validasi taruhan
        const betAmount = parseInt(uiElements.betInput.value);
        if (betAmount < 100 || betAmount > gameState.balance) {
            alert('Nilai taruhan tidak valid! Pastikan taruhan minimal $100 dan tidak melebihi saldo Anda.');
            return;
        }
        // Atur state taruhan dan kurangi saldo
        gameState.currentBet = betAmount;
        gameState.balance -= gameState.currentBet;
        
        // Pindahkan tampilan ke layar permainan
        updateView('gameScreen');
        
        // Reset semua state permainan dari ronde sebelumnya
        gameState.deck = createDeck();
        shuffle(gameState.deck);
        gameState.playerHand = [];
        gameState.botHand = [];
        gameState.discardPile = [];
        gameState.isPlayerUnoCalled = false;
        gameState.botHasCalledUno = false;
        clearTimeout(gameState.playerUnoTimer); // Hentikan timer yang mungkin masih berjalan
        clearTimeout(gameState.playerCallUnoTimer);
        uiElements.callUnoOnBotButton.classList.add('hidden');
        
        // Bagikan 7 kartu awal ke pemain dan bot
        for (let i = 0; i < 7; i++) {
            drawCards(gameState.playerHand, 1);
            drawCards(gameState.botHand, 1);
        }

        // Ambil kartu pertama untuk discard pile, pastikan bukan kartu Wild
        let firstCard = gameState.deck.pop();
        while (firstCard.color === 'black') {   
            gameState.deck.push(firstCard); // Kembalikan kartu Wild ke deck
            shuffle(gameState.deck); // Kocok ulang
            firstCard = gameState.deck.pop(); // Ambil kartu baru
        }
        gameState.discardPile.push(firstCard);
        
        // Atur giliran pertama untuk pemain
        gameState.currentPlayer = 'player';
        // Gambar ulang seluruh tampilan permainan
        renderGame();
    }

    /**
     * Memeriksa apakah sebuah kartu bisa dimainkan di atas kartu teratas.
     * @param {object} card - Kartu yang ingin dimainkan.
     * @param {object} topCard - Kartu teratas di discard pile.
     * @returns {boolean} - True jika kartu bisa dimainkan.
     */
    function isCardPlayable(card, topCard) {
        // Kartu Wild (warna hitam) selalu bisa dimainkan.
        if (card.color === 'black') return true;
        // Kartu bisa dimainkan jika warnanya sama ATAU nilainya (angka/aksi) sama.
        return card.color === topCard.color || card.value === topCard.value;
    }

    /**
     * Mengambil sejumlah kartu dari draw pile ke tangan pemain/bot.
     * @param {Array} hand - Tangan yang akan menerima kartu (gameState.playerHand atau gameState.botHand).
     * @param {number} amount - Jumlah kartu yang diambil.
     */
    function drawCards(hand, amount) {
        for (let i = 0; i < amount; i++) {
            // Jika draw pile habis, kocok ulang discard pile menjadi draw pile baru.
            if (gameState.deck.length === 0) {
                const top = gameState.discardPile.pop(); // Simpan kartu teratas
                gameState.deck = [...gameState.discardPile];
                shuffle(gameState.deck);
                gameState.discardPile = [top]; // Kembalikan kartu teratas
            }
            // Jika masih ada kartu di deck, ambil satu.
            if (gameState.deck.length > 0) {
                hand.push(gameState.deck.pop());
            }
        }
    }

    /**
     * Menangani efek khusus dari kartu aksi (skip, reverse, drawTwo, wild).
     * @param {object} card - Kartu yang baru saja dimainkan.
     * @param {string} playedBy - Siapa yang memainkan kartu ('player' atau 'bot').
     */
    function handleCardEffect(card, playedBy) {
        // Cek pemenang setelah kartu dimainkan, sebelum efek dijalankan.
        if (checkForWinner()) return;

        let nextPlayer = (playedBy === 'player') ? 'bot' : 'player';
        const opponentHand = (playedBy === 'player') ? gameState.botHand : gameState.playerHand;

        switch (card.value) {
            case 'skip':
            case 'reverse': // Dalam game 2 pemain, reverse berfungsi sama seperti skip.
                nextPlayer = playedBy; // Pemain yang sama main lagi.
                break;
            case 'drawTwo':
                drawCards(opponentHand, 2);
                nextPlayer = playedBy; // Lawan kena +2 dan gilirannya dilewati.
                break;
            case 'wild':
                if (playedBy === 'player') {
                    updateView('colorPicker'); // Tampilkan modal pemilih warna untuk pemain.
                    return; // Hentikan fungsi di sini, giliran akan dilanjutkan setelah warna dipilih.
                } else {
                    // Bot memilih warna secara acak.
                    const colors = ['red', 'green', 'blue', 'yellow'];
                    const chosenColor = colors[Math.floor(Math.random() * colors.length)];
                    gameState.discardPile[gameState.discardPile.length - 1].color = chosenColor;
                }
                break;
            case 'wildDrawFour':
                drawCards(opponentHand, 4);
                if (playedBy === 'player') {
                    updateView('colorPicker'); // Tampilkan modal untuk pemain.
                    return;
                } else {
                    // Bot memilih warna secara acak.
                    const colors = ['red', 'green', 'blue', 'yellow'];
                    const chosenColor = colors[Math.floor(Math.random() * colors.length)];
                    gameState.discardPile[gameState.discardPile.length - 1].color = chosenColor;
                }
                break;
        }
        // Pindahkan giliran ke pemain selanjutnya.
        switchTurn(nextPlayer);
    }
    
    /**
     * Logika untuk giliran Bot (AI Sederhana).
     */
    function botTurn() {
        const topCard = gameState.discardPile[gameState.discardPile.length - 1];
        // Cari kartu pertama yang bisa dimainkan oleh bot.
        let cardToPlayIndex = gameState.botHand.findIndex(card => isCardPlayable(card, topCard));
        
        // Logika tambahan: Bot tidak akan memainkan Wild Draw Four jika punya kartu lain yang valid.
        if (cardToPlayIndex !== -1 && gameState.botHand[cardToPlayIndex].value === 'wildDrawFour') {
            const hasOtherOptions = gameState.botHand.some(c => c.value !== 'wildDrawFour' && isCardPlayable(c, topCard));
            if (hasOtherOptions) {
                // Cari lagi kartu valid yang bukan Wild Draw Four.
                cardToPlayIndex = gameState.botHand.findIndex(card => isCardPlayable(card, topCard) && card.value !== 'wildDrawFour');
            }
        }

        // Reset status UNO bot setiap giliran.
        gameState.botHasCalledUno = false;
        
        // Jika ada kartu yang bisa dimainkan...
        if (cardToPlayIndex !== -1) {
            const card = gameState.botHand.splice(cardToPlayIndex, 1)[0];
            gameState.discardPile.push(card);

            // Jika kartu bot sisa 1, bot akan "memanggil" UNO (dengan probabilitas 80%).
            if (gameState.botHand.length === 1) {
                if (Math.random() < 0.8) { // 80% kemungkinan bot ingat UNO.
                    gameState.botHasCalledUno = true;
                }
                // Mulai timer agar pemain punya kesempatan menangkap bot.
                startPlayerCallUnoTimer();
            }

            handleCardEffect(card, 'bot');
        } else {
            // Jika tidak ada kartu, bot ambil 1 kartu.
            drawCards(gameState.botHand, 1);
            const newCard = gameState.botHand[gameState.botHand.length - 1];
            // Cek apakah kartu yang baru diambil bisa langsung dimainkan.
            if (newCard && isCardPlayable(newCard, topCard)) {
                 const card = gameState.botHand.pop();
                 gameState.discardPile.push(card);
                 handleCardEffect(card, 'bot');
            } else {
                 // Jika tidak bisa, langsung oper giliran ke pemain.
                 switchTurn('player');
            }
        }
    }

    /**
     * Mengganti giliran dan memicu giliran bot jika diperlukan.
     * @param {string} nextPlayer - 'player' atau 'bot'.
     */
    function switchTurn(nextPlayer) {
        gameState.currentPlayer = nextPlayer;
        
        if (gameState.currentPlayer === 'bot') {
            // Sembunyikan tombol untuk memanggil UNO bot, karena sudah giliran bot.
            uiElements.callUnoOnBotButton.classList.add('hidden');
            clearTimeout(gameState.playerCallUnoTimer);
            renderGame(); // Update tampilan sebelum bot bergerak
            // Beri jeda 1 detik sebelum bot bergerak agar tidak terasa instan.
            setTimeout(botTurn, 1000);
        } else {
            renderGame(); // Jika giliran pemain, cukup update tampilan.
        }
    }

    /**
     * Logika saat pemain memainkan kartu dari tangannya.
     * @param {number} cardIndex - Posisi/index kartu di array gameState.playerHand.
     */
    function playerPlayCard(cardIndex) {
        // Pastikan ini adalah giliran pemain.
        if (gameState.currentPlayer !== 'player') return;

        const card = gameState.playerHand[cardIndex];
        const topCard = gameState.discardPile[gameState.discardPile.length - 1];
        
        // Validasi aturan Wild Draw Four: tidak boleh dimainkan jika ada kartu lain yang cocok dengan warna kartu teratas.
        if (card.value === 'wildDrawFour') {
            const hasOtherPlayableCard = gameState.playerHand.some(c => c.value !== 'wildDrawFour' && c.color === topCard.color);
            if (hasOtherPlayableCard) {
                alert('Anda tidak bisa memainkan kartu +4 jika masih punya kartu lain dengan warna yang sama!');
                return;
            }
        }

        // Cek apakah kartu valid untuk dimainkan.
        if (isCardPlayable(card, topCard)) {
            gameState.playerHand.splice(cardIndex, 1); // Hapus kartu dari tangan pemain.
            gameState.discardPile.push(card); // Letakkan di tumpukan discard.
            
            // Jika kartu sisa 1, mulai timer penalti UNO.
            if (gameState.playerHand.length === 1) {
                startPlayerUnoTimer();
            } else {
                // Jika kartu > 1, pastikan tidak ada timer UNO yang aktif.
                clearTimeout(gameState.playerUnoTimer);
            }
            // Reset status UNO pemain setelah main kartu.
            gameState.isPlayerUnoCalled = false;
            
            handleCardEffect(card, 'player');
        } else {
            alert('Kartu tidak valid!');
        }

    }

    /**
     * Memeriksa apakah ada pemenang di akhir setiap giliran.
     * @returns {boolean} - True jika ada pemenang.
     */
    function checkForWinner() {f
        let winner = null;
        if (gameState.playerHand.length === 0) winner = 'Pemain';
        if (gameState.botHand.length === 0) winner = 'Bot';

        if (winner) {
            // Hentikan semua timer yang mungkin berjalan.
            clearTimeout(gameState.playerUnoTimer);
            clearTimeout(gameState.playerCallUnoTimer);
            // Beri jeda sesaat sebelum menampilkan pesan menang/kalah.
            setTimeout(() => {
                if (winner === 'Pemain') {
                    alert('Selamat, Anda memenangkan ronde ini!');
                    gameState.balance += gameState.currentBet * 2; // Pemain dapat 2x lipat taruhan.
                } else {
                    alert('Sayang sekali, Bot memenangkan ronde ini.');
                }
                
                // Cek jika saldo habis (Game Over).
                if (gameState.balance <= 0) {
                    updateView('gameOverScreen');
                } else {
                    // Jika belum, kembali ke layar awal untuk ronde baru.
                    updateView('startScreen');
                }
                updateInfoBar();
            }, 500);
            return true;
        }
        return false;
    }

    /**
     * Memulai timer 5 detik. Jika pemain tidak menekan UNO dalam waktu ini, mereka akan dihukum.
     */
    function startPlayerUnoTimer() {
        gameState.playerUnoTimer = setTimeout(() => {
            if (!gameState.isPlayerUnoCalled) {
                alert("Anda lupa menekan UNO! Penalti +2 kartu.");
                drawCards(gameState.playerHand, 2);
                renderGame();
            }
        }, 5000); // 5000 milidetik = 5 detik
    }

    /**
     * Memulai timer 3 detik yang memberi pemain kesempatan untuk menangkap bot yang lupa UNO.
     */
    function startPlayerCallUnoTimer() {
        uiElements.callUnoOnBotButton.classList.remove('hidden');
        gameState.playerCallUnoTimer = setTimeout(() => {
            uiElements.callUnoOnBotButton.classList.add('hidden');
        }, 3000); // 3000 milidetik = 3 detik
    }
    
    // =================================================================================
    // BAGIAN 4: FUNGSI-FUNGSI UNTUK MERENDER / MENGGAMBAR TAMPILAN
    // Fungsi-fungsi ini bertanggung jawab untuk memperbarui tampilan visual berdasarkan 'gameState'.
    // =================================================================================

    /**
     * Fungsi utama untuk me-render seluruh state permainan ke layar.
     */
    function renderGame() {
        renderPlayerHand();
        renderBotHand();
        renderDiscardPile();
        updateInfoBar();
        toggleUnoButton();
    }

    /**
     * Menggambar kartu di tangan pemain.
     */
    function renderPlayerHand() {
        uiElements.playerHand.innerHTML = ''; // Kosongkan area tangan pemain terlebih dahulu.
        gameState.playerHand.forEach((card, index) => {
            const cardEl = createCardElement(card);
            // Tambahkan event listener agar kartu bisa diklik untuk dimainkan.
            cardEl.addEventListener('click', () => playerPlayCard(index));
            uiElements.playerHand.appendChild(cardEl);
        });
    }

    /**
     * Menggambar kartu di tangan bot (hanya bagian belakangnya).
     */
    function renderBotHand() {
        uiElements.botHand.innerHTML = ''; // Kosongkan area tangan bot.
        gameState.botHand.forEach(() => {
            // Kita hanya perlu tahu jumlah kartu bot, bukan detailnya. Jadi kita buat elemen kartu belakang.
            const cardEl = createCardElement({ back: true });
            uiElements.botHand.appendChild(cardEl);
        });
    }
    
    /**
     * Menggambar kartu teratas di tumpukan discard.
     */
    function renderDiscardPile() {
        uiElements.discardPile.innerHTML = '';
        const topCard = gameState.discardPile[gameState.discardPile.length - 1];
        
        // Hapus class cincin warna dari render sebelumnya.
        const colorClasses = ['ring-red-500', 'ring-green-500', 'ring-blue-500', 'ring-yellow-500', 'ring-gray-700'];
        uiElements.discardPile.classList.remove(...colorClasses, 'ring-4', 'rounded-lg');

        if (topCard) {
            uiElements.discardPile.appendChild(createCardElement(topCard));
            
            // Tambahkan cincin warna sesuai warna aktif (penting untuk kartu Wild).
            const ringColorMap = {
                red: 'ring-red-500', green: 'ring-green-500', blue: 'ring-blue-500', yellow: 'ring-yellow-500', black: 'ring-gray-700'
            };
            const activeColorClass = ringColorMap[topCard.color];
            if (activeColorClass) {
                uiElements.discardPile.classList.add('ring-4', 'rounded-lg', activeColorClass);
            }
        }
    }

    /**
     * Memperbarui teks di bar informasi (saldo, taruhan, giliran).
     */
    function updateInfoBar() {
        uiElements.balance.textContent = gameState.balance;
        uiElements.currentBet.textContent = gameState.currentBet;
        uiElements.turnIndicator.textContent = gameState.currentPlayer === 'player' ? 'Anda' : 'Bot';
        uiElements.modalBalance.textContent = gameState.balance;
    }

    /**
     * Menampilkan atau menyembunyikan tombol UNO.
     */
    function toggleUnoButton() {
        // Tombol UNO muncul saat pemain akan memainkan kartu yang membuat tangannya sisa 1 (artinya, saat ini kartunya ada 2).
        const isVisible = gameState.playerHand.length === 2;
        uiElements.unoButton.classList.toggle('hidden', !isVisible);
    }

    /**
     * Fungsi pembantu untuk membuat elemen <img> sebuah kartu.
     * @param {object} card - Objek kartu. Bisa juga { back: true } untuk kartu belakang.
     * @returns {HTMLElement} - Elemen <img> yang sudah jadi.
     */
    function createCardElement(card) {
        const cardClasses = "w-20 h-28 md:w-24 md:h-36 lg:w-28 lg:h-40 rounded-lg shadow-md transition-all duration-200 hover:-translate-y-2 hover:shadow-xl cursor-pointer";
        const imgEl = document.createElement('img');
        imgEl.className = cardClasses;
        
        if (card.back) {
            imgEl.src = 'assets/cards/back.png';
            imgEl.alt = 'Kartu UNO belakang';
        } else {
            // Menentukan nama file gambar berdasarkan warna dan nilai kartu.
            let fileName;
            const isWild = card.value === 'wild' || card.value === 'wildDrawFour';
            if (isWild) {
                fileName = `${card.value}.png`;
            } else {
                fileName = `${card.color}_${card.value}.png`;
            }
            imgEl.src = `assets/cards/${fileName}`;
            imgEl.alt = `Kartu ${card.color} ${card.value}`;
        }
        return imgEl;
    }

    // =================================================================================
    // BAGIAN 5: INISIALISASI DAN EVENT LISTENERS
    // Bagian ini "menghidupkan" semua tombol dan elemen interaktif.
    // =================================================================================
    
    /**
     * Mendaftarkan semua event listener (aksi klik) ke elemen-elemen yang relevan.
     */
    function initializeEventListeners() {
        // Tombol untuk memulai ronde baru.
        uiElements.startGameButton.addEventListener('click', startGame);

        // Tombol untuk me-restart game setelah Game Over.
        uiElements.restartGameButton.addEventListener('click', () => {
            gameState.balance = 5000; // Kembalikan saldo ke awal.
            updateView('startScreen');
            updateInfoBar();
        });

        // Aksi klik pada tumpukan kartu untuk mengambil kartu.
        uiElements.drawPile.addEventListener('click', () => {
            if (gameState.currentPlayer !== 'player') return; // Hanya bisa dilakukan di giliran pemain.
            drawCards(gameState.playerHand, 1);
            
            const newCard = gameState.playerHand[gameState.playerHand.length - 1];
            const topCard = gameState.discardPile[gameState.discardPile.length - 1];
            // Jika kartu yang baru diambil tidak bisa dimainkan, langsung ganti giliran.
            if (newCard && !isCardPlayable(newCard, topCard)) {
                 setTimeout(() => switchTurn('bot'), 500); // Beri jeda sedikit.
            }
            renderGame();
        });

        // Event listener untuk semua tombol pemilih warna di modal.
        document.querySelectorAll('.color-choice').forEach(button => {
            button.addEventListener('click', (e) => {
                const chosenColor = e.target.dataset.color;
                gameState.discardPile[gameState.discardPile.length - 1].color = chosenColor;
                updateView('gameScreen');
                
                // Setelah memilih warna, giliran berpindah.
                const lastCard = gameState.discardPile[gameState.discardPile.length - 1];
                // Jika kartu adalah +4 atau +2, pemain yang sama dapat giliran lagi (aturan UNO).
                // Di sini, kita asumsikan lawan yang kena efek +4 dan gilirannya dilewati.
                if (lastCard.value === 'wildDrawFour') {
                    // Dalam implementasi ini, setelah pemain memilih warna untuk +4, giliran tetap ke bot.
                    // Namun, efek +4 sudah terjadi pada bot.
                    switchTurn('bot');
                } else {
                    switchTurn('bot');
                }
            });
        });

        // Tombol untuk memanggil "UNO!".
        uiElements.unoButton.addEventListener('click', () => {
            // Cek apakah pemain benar-benar punya 1 kartu dan timernya masih berjalan.
            if (gameState.playerHand.length === 1 && gameState.playerUnoTimer) {
                gameState.isPlayerUnoCalled = true;
                clearTimeout(gameState.playerUnoTimer); // Hentikan timer penalti.
                alert("UNO!");
            }
        });

        // Tombol untuk menangkap bot yang lupa UNO.
        uiElements.callUnoOnBotButton.addEventListener('click', () => {
            if (gameState.botHand.length === 1 && !gameState.botHasCalledUno) {
                alert("Berhasil! Anda menangkap Bot lupa UNO. Bot mengambil 2 kartu penalti.");
                drawCards(gameState.botHand, 2);
                renderGame();
            } else if (gameState.botHand.length === 1 && gameState.botHasCalledUno) {
                alert("Gagal! Bot sudah memanggil UNO.");
            }
            // Sembunyikan tombol setelah diklik, apa pun hasilnya.
            uiElements.callUnoOnBotButton.classList.add('hidden');
            clearTimeout(gameState.playerCallUnoTimer);
        });
    }

    /**
     * Fungsi inisialisasi utama yang dipanggil saat script pertama kali dijalankan.
     */
    function init() {
        initializeEventListeners();
        updateView('startScreen');
        updateInfoBar();
    }

    // Jalankan fungsi inisialisasi.
    init();
});