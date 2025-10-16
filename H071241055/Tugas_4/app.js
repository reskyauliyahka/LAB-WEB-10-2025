document.addEventListener("DOMContentLoaded", () => {
  //NOSONAR
  // === ELEMEN DOM ===
  const playerHandContainer = document.getElementById("player-hand");
  const botHandContainer = document.getElementById("bot-hand");
  const discardPileCard = document.getElementById("discard-pile-card");
  const deckElement = document.getElementById("deck");
  const playerArea = document.getElementById("player-area");
  const botArea = document.getElementById("bot-area");
  const colorPickerModal = document.getElementById("color-picker-modal");
  const colorChoices = document.querySelectorAll(".color-choice");
  const betAmountInput = document.getElementById("bet-amount");
  const messageArea = document.getElementById("message-area");
  const balanceElement = document.getElementById("balance");
  const gameOverModal = document.getElementById("game-over-modal");
  const winModal = document.getElementById("win-modal");
  const winMessage = document.getElementById("win-message");
  const gameOverMessage = document.getElementById("game-over-message");
  const restartGameButton = document.getElementById("restart-game-button");
  const passButton = document.getElementById("pass-button");
  const unoButton = document.getElementById("uno-button");
  const callUnoButton = document.getElementById("call-uno-button");

  // === Elemen Modal Awal ===
  const startRoundModal = document.getElementById("start-round-modal");
  const startGameButton = document.getElementById("start-game-button");
  const initialBetAmountInput = document.getElementById("initial-bet-amount");
  const startModalMessage = document.getElementById("start-modal-message");
  const startBalanceDisplay = document.getElementById("start-balance-display");

  // === KONSTANTA PERMAINAN ===
  const CARD_VALUES = {
    SKIP: "skip",
    REVERSE: "reverse",
    DRAW_TWO: "plus2",
    WILD: "wild",
    WILD_DRAW_FOUR: "wild-draw-four",
  };
  const COLORS = ["red", "yellow", "green", "blue"];

  // === STATUS PERMAINAN ===
  let deck = [];
  let playerHand = [];
  let botHand = [];
  let discardPile = [];
  let currentPlayer = "player";
  let balance = 5000;
  let currentBet = 0;
  let canPass = false;
  let isDrawing = false;
  let unoCalled = { player: false, bot: false };
  let unoTimeout = null;
  let botMustCallUno = false;
  let botUnoPenaltyTimeout = null;

  // =================================================================================
  // FUNGSI INISIALISASI & RENDER
  // =================================================================================

  /**
   * Membuat dek kartu UNO standar yang terdiri dari kartu angka, kartu aksi, dan kartu wild.
   * @returns {Array<Object>} Sebuah array objek kartu yang membentuk dek lengkap.
   * Setiap objek kartu memiliki properti `color` dan `value`.
   */
  function createDeck() {
    const values = [
      "0",
      "1",
      "2",
      "3",
      "4",
      "5",
      "6",
      "7",
      "8",
      "9",
      CARD_VALUES.SKIP,
      CARD_VALUES.REVERSE,
      CARD_VALUES.DRAW_TWO,
    ];
    let createdDeck = [];
    for (const color of COLORS) {
      for (const value of values) {
        createdDeck.push({ color, value });
      }
    }
    for (let i = 0; i < 4; i++) {
      createdDeck.push({ color: CARD_VALUES.WILD, value: CARD_VALUES.WILD });
      createdDeck.push({
        color: CARD_VALUES.WILD,
        value: CARD_VALUES.WILD_DRAW_FOUR,
      });
    }
    return createdDeck;
  }

  /**
   * Mengacak urutan kartu dalam sebuah dek menggunakan algoritma Fisher-Yates.
   * @param {Array<Object>} deckToShuffle - Array kartu yang akan diacak.
   * @returns {Array<Object>} Array kartu yang sudah diacak.
   */
  function shuffleDeck(deckToShuffle) {
    for (let i = deckToShuffle.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [deckToShuffle[i], deckToShuffle[j]] = [
        deckToShuffle[j],
        deckToShuffle[i],
      ];
    }
    return deckToShuffle;
  }

  /**
   * Merender kartu di tangan pemain atau bot ke antarmuka pengguna (UI).
   * Juga menangani logika untuk menampilkan tombol "UNO!" dan penalti jika lupa.
   * @param {Array<Object>} hand - Array kartu di tangan pemain/bot.
   * @param {HTMLElement} container - Elemen kontainer HTML untuk menampilkan kartu.
   * @param {boolean} isPlayer - True jika ini adalah tangan pemain, false jika bot.
   */
  function renderHand(hand, container, isPlayer) {
    container.innerHTML = "";
    hand.forEach((card, index) => {
      const cardElement = createCardElement(card, isPlayer);
      if (isPlayer) {
        cardElement.dataset.index = index;
      }
      container.appendChild(cardElement);
    });

    if (botUnoPenaltyTimeout) {
      clearTimeout(botUnoPenaltyTimeout);
      botMustCallUno = false;
      callUnoButton.classList.add("hidden");
    }

    if (hand.length === 1) {
      if (isPlayer && !unoCalled.player) {
        showMessage("You have 1 card left! Call UNO within 5 seconds!");
        unoButton.classList.remove("hidden");
        unoTimeout = setTimeout(() => {
          if (!unoCalled.player) {
            showMessage("You forgot to call UNO! Penalty: +2 cards.");
            drawCards(playerHand, 2);
            renderHand(playerHand, playerHandContainer, true);
          }
          unoButton.classList.add("hidden");
        }, 5000);
      } else if (!isPlayer && !unoCalled.bot) {
        showMessage("Bot has 1 card left! Call UNO on them!");
        botMustCallUno = true;
        callUnoButton.classList.remove("hidden");
        botUnoPenaltyTimeout = setTimeout(() => {
          if (botMustCallUno) {
            showMessage("Bot called UNO safely.");
            unoCalled.bot = true;
            botMustCallUno = false;
            callUnoButton.classList.add("hidden");
          }
        }, 3000);
      }
    } else {
      if (isPlayer) {
        unoCalled.player = false;
        unoButton.classList.add("hidden");
        if (unoTimeout) clearTimeout(unoTimeout);
      } else {
        unoCalled.bot = false;
      }
    }
  }

  /**
   * Membuat elemen gambar (<img>) untuk sebuah kartu.
   * @param {Object} card - Objek kartu yang akan dirender.
   * @param {boolean} isPlayer - True jika kartu milik pemain (menampilkan gambar kartu), false jika milik bot (menampilkan punggung kartu).
   * @returns {HTMLImageElement} Elemen <img> yang sudah dikonfigurasi.
   */
  function createCardElement(card, isPlayer) {
    const cardElement = document.createElement("img");
    cardElement.className = "card";
    if (isPlayer) {
      let filename = `${card.color}_${card.value}.png`;
      if (card.value === CARD_VALUES.WILD_DRAW_FOUR) filename = "plus_4.png";
      else if (card.value === CARD_VALUES.WILD) filename = "wild.png";
      cardElement.src = `assets/${filename}`;
      cardElement.alt = `${card.color} ${card.value}`;
    } else {
      cardElement.src = "assets/card_back.png";
      cardElement.alt = "Card Back";
    }
    return cardElement;
  }

  /**
   * Merender kartu teratas dari tumpukan buangan (discard pile) ke UI.
   */
  function renderDiscardPile() {
    const topCard = discardPile[discardPile.length - 1];
    discardPileCard.innerHTML = "";
    const cardElement = document.createElement("img");
    cardElement.className = "card";
    let filename = `${topCard.color}_${topCard.value}.png`;
    if (topCard.value === CARD_VALUES.WILD_DRAW_FOUR) filename = "plus_4.png";
    else if (topCard.value === CARD_VALUES.WILD) filename = "wild.png";
    cardElement.src = `assets/${filename}`;
    cardElement.alt = `${topCard.color} ${topCard.value}`;
    discardPileCard.appendChild(cardElement);
  }

  /**
   * Memperbarui tampilan saldo pemain di UI.
   */
  function updateBalance() {
    const balanceText = `$${balance}`;
    balanceElement.textContent = balanceText;
    startBalanceDisplay.textContent = balanceText;
  }

  // =================================================================================
  // FUNGSI LOGIKA PERMAINAN
  // =================================================================================

  /**
   * Memeriksa apakah sebuah kartu dapat dimainkan di atas kartu teratas tumpukan buangan.
   * @param {Object} card - Kartu yang ingin dimainkan.
   * @param {Object} topCard - Kartu teratas di tumpukan buangan.
   * @returns {boolean} True jika langkah valid, false jika tidak.
   */
  function isMoveValid(card, topCard) {
    const activeColor = topCard.chosenColor || topCard.color;
    if (card.color === CARD_VALUES.WILD) return true;
    return card.color === activeColor || card.value === topCard.value;
  }

  /**
   * Memeriksa apakah kartu Wild Draw Four (+4) boleh dimainkan.
   * Aturan: Hanya boleh dimainkan jika pemain tidak memiliki kartu lain yang cocok dengan warna atau angka kartu di tumpukan.
   * @param {Array<Object>} hand - Tangan pemain.
   * @param {Object} topCard - Kartu teratas di tumpukan buangan.
   * @returns {boolean} True jika Wild Draw Four boleh dimainkan.
   */
  function canPlayWildDrawFour(hand, topCard) {
    return !hand.some(
      (c) => c.value !== CARD_VALUES.WILD_DRAW_FOUR && isMoveValid(c, topCard)
    );
  }

  /**
   * Menambahkan sejumlah kartu dari dek ke tangan pemain.
   * Jika dek habis, tumpukan buangan akan diacak ulang menjadi dek baru.
   * @param {Array<Object>} player - Tangan pemain (atau bot) yang akan menerima kartu.
   * @param {number} numCards - Jumlah kartu yang akan diambil.
   */
  function drawCards(player, numCards) {
    for (let i = 0; i < numCards; i++) {
      if (deck.length === 0) {
        const topCard = discardPile.pop();
        deck = shuffleDeck(discardPile);
        discardPile = [topCard];
      }
      if (deck.length > 0) player.push(deck.pop());
    }
  }

  /**
   * Mengganti giliran dari pemain saat ini ke pemain berikutnya.
   * Jika giliran bot, fungsi `botTurn` akan dipanggil.
   */
  function switchTurn() {
    currentPlayer = currentPlayer === "player" ? "bot" : "player";
    if (currentPlayer === "bot") {
      setTimeout(botTurn, 1500);
    }
    updateTurnIndicator();
  }

  /**
   * Memperbarui indikator visual di UI untuk menunjukkan giliran siapa saat ini.
   */
  function updateTurnIndicator() {
    if (currentPlayer === "player") {
      playerArea.classList.add("active");
      botArea.classList.remove("active");
    } else {
      botArea.classList.add("active");
      playerArea.classList.remove("active");
    }
  }

  /**
   * Menjalankan logika saat pemain atau bot memainkan sebuah kartu.
   * @param {Object} card - Kartu yang dimainkan.
   * @param {Array<Object>} hand - Tangan asal kartu tersebut.
   * @param {number} cardIndex - Indeks kartu di dalam tangan.
   * @param {boolean} isPlayer - True jika pemain yang memainkan kartu, false jika bot.
   */
  function playCard(card, hand, cardIndex, isPlayer) {
    canPass = false;
    passButton.classList.add("hidden");
    hand.splice(cardIndex, 1);
    discardPile.push(card); // Kartu ditambahkan ke tumpukan
    renderDiscardPile();
    renderHand(playerHand, playerHandContainer, true);
    renderHand(botHand, botHandContainer, false);

    if (!isPlayer && botUnoPenaltyTimeout) {
      clearTimeout(botUnoPenaltyTimeout);
      callUnoButton.classList.add("hidden");
      botMustCallUno = false;
    }

    // Pengecekan pemenang harus dilakukan SEBELUM efek kartu
    if (checkForWinner()) {
      return;
    }

    // Penalti UNO juga harus dicek SEBELUM efek kartu
    if (isPlayer && hand.length === 1 && !unoCalled.player) {
      // Logika ini sudah benar, hanya posisinya yang perlu dipastikan
      // sebelum handleCardEffect
    }

    handleCardEffect(card);

    // Baris yang menyebabkan bug telah dihapus.
    // Blok ini hanya mereset status UNO bot.
    if (!isPlayer) {
      unoCalled.bot = false;
    }
  }

  /**
   * Menangani efek khusus dari kartu aksi (Skip, Reverse, Draw Two, Wild, Wild Draw Four).
   * @param {Object} card - Kartu yang efeknya akan dijalankan.
   */
  function handleCardEffect(card) {
    const opponent = currentPlayer === "player" ? "bot" : "player";
    const opponentHand = opponent === "bot" ? botHand : playerHand;
    const opponentName = opponent.charAt(0).toUpperCase() + opponent.slice(1);

    switch (card.value) {
      case CARD_VALUES.SKIP:
      case CARD_VALUES.REVERSE:
        showMessage(`${opponentName}'s turn is skipped!`);
        if (currentPlayer === "bot") setTimeout(botTurn, 1500);
        break;
      case CARD_VALUES.DRAW_TWO:
        showMessage(`${opponentName} draws 2 cards and is skipped!`);
        drawCards(opponentHand, 2);
        renderHand(playerHand, playerHandContainer, true);
        renderHand(botHand, botHandContainer, false);
        if (currentPlayer === "bot") setTimeout(botTurn, 1500);
        break;
      case CARD_VALUES.WILD:
        if (currentPlayer === "player") {
          colorPickerModal.classList.add("visible");
        } else {
          const chosenColor = COLORS[Math.floor(Math.random() * COLORS.length)];
          setWildColor(chosenColor, true);
        }
        break;
      case CARD_VALUES.WILD_DRAW_FOUR:
        showMessage(`${opponentName} draws 4 cards and is skipped!`);
        drawCards(opponentHand, 4);
        renderHand(playerHand, playerHandContainer, true);
        renderHand(botHand, botHandContainer, false);
        if (currentPlayer === "player") {
          colorPickerModal.classList.add("visible");
        } else {
          const chosenColor = COLORS[Math.floor(Math.random() * COLORS.length)];
          setWildColor(chosenColor, true);
        }
        break;
      default:
        switchTurn();
        break;
    }
  }

  /**
   * Mengatur warna pilihan setelah kartu Wild atau Wild Draw Four dimainkan.
   * Juga memperbarui tampilan kartu di tumpukan buangan untuk menunjukkan warna yang aktif.
   * @param {string} color - Warna yang dipilih ('red', 'yellow', 'green', 'blue').
   * @param {boolean} shouldSwitchTurn - True jika giliran harus beralih setelah memilih warna.
   */
  function setWildColor(color, shouldSwitchTurn) {
    const topCard = discardPile[discardPile.length - 1];
    topCard.chosenColor = color;
    const discardImg = discardPileCard.querySelector("img");
    if (discardImg) {
      discardImg.style.border = `4px solid ${color}`;
    }
    const playerName =
      currentPlayer.charAt(0).toUpperCase() + currentPlayer.slice(1);
    showMessage(`${playerName} chose ${color}.`);
    if (shouldSwitchTurn) {
      switchTurn();
    }
  }

  // =================================================================================
  // LOGIKA BOT
  // =================================================================================

  /**
   * Menjalankan logika untuk giliran bot (AI).
   * Bot akan mencari kartu yang bisa dimainkan, jika tidak ada, bot akan mengambil kartu.
   */
  function botTurn() {
    const topCard = discardPile[discardPile.length - 1];
    let playableCards = botHand.filter((card) => isMoveValid(card, topCard));
    let cardToPlay = null;

    let nonWildPlayable = playableCards.filter((c) => c.color !== "wild");
    if (nonWildPlayable.length > 0) {
      cardToPlay =
        nonWildPlayable[Math.floor(Math.random() * nonWildPlayable.length)];
    } else if (playableCards.some((c) => c.value === CARD_VALUES.WILD)) {
      cardToPlay = playableCards.find((c) => c.value === CARD_VALUES.WILD);
    } else if (
      playableCards.some((c) => c.value === CARD_VALUES.WILD_DRAW_FOUR)
    ) {
      if (canPlayWildDrawFour(botHand, topCard)) {
        cardToPlay = playableCards.find(
          (c) => c.value === CARD_VALUES.WILD_DRAW_FOUR
        );
      }
    }

    if (cardToPlay) {
      const cardIndex = botHand.indexOf(cardToPlay);
      showMessage(`Bot plays a ${cardToPlay.color} ${cardToPlay.value}.`);
      playCard(cardToPlay, botHand, cardIndex, false);
    } else {
      showMessage("Bot draws a card.");
      drawCards(botHand, 1);
      renderHand(botHand, botHandContainer, false);
      const newCard = botHand[botHand.length - 1];
      if (isMoveValid(newCard, topCard)) {
        setTimeout(() => {
          showMessage(`Bot plays the drawn card!`);
          const cardIndex = botHand.length - 1;
          playCard(newCard, botHand, cardIndex, false);
        }, 1000);
      } else {
        switchTurn();
      }
    }
  }

  // =================================================================================
  // KONDISI MENANG/KALAH
  // =================================================================================

  /**
   * Memeriksa apakah ada pemain yang sudah kehabisan kartu.
   * @returns {boolean} True jika ada pemenang, false jika tidak.
   */
  function checkForWinner() {
    if (playerHand.length === 0) {
      endRound("player");
      return true;
    }
    if (botHand.length === 0) {
      endRound("bot");
      return true;
    }
    return false;
  }

  /**
   * Mengakhiri ronde permainan saat ada pemenang.
   * Menghitung kemenangan/kekalahan taruhan, menampilkan pesan, dan menyiapkan ronde berikutnya atau game over.
   * @param {string} winner - Pemenang ronde ('player' atau 'bot').
   */
  function endRound(winner) {
    if (winner === "player") {
      balance += currentBet;
      winMessage.textContent = `You Won! +$${currentBet}`;
      winModal.classList.add("visible");
      updateBalance();
      setTimeout(() => {
        winModal.classList.remove("visible");
        startModalMessage.textContent = "";
        initialBetAmountInput.value = "100";
        startRoundModal.classList.add("visible");
      }, 3000);
    } else {
      balance -= currentBet;
      showMessage(`You lost the round! -$${currentBet}`);
      updateBalance();
      if (balance <= 0) {
        gameOverModal.classList.add("visible");
        gameOverMessage.textContent = "Game Over!";
      } else {
        setTimeout(() => {
          startModalMessage.textContent = "";
          initialBetAmountInput.value = "100";
          startRoundModal.classList.add("visible");
        }, 3000);
      }
    }
  }

  /**
   * Menampilkan pesan status permainan di area pesan UI.
   * @param {string} msg - Pesan yang akan ditampilkan.
   */
  function showMessage(msg) {
    messageArea.textContent = msg;
  }

  // =================================================================================
  // EVENT LISTENERS
  // =================================================================================

  /**
   * Event listener untuk klik pada kartu di tangan pemain.
   */
  playerHandContainer.addEventListener("click", (e) => {
    if (currentPlayer !== "player") return;
    const cardElement = e.target.closest(".card");
    if (!cardElement) return;

    const cardIndex = parseInt(cardElement.dataset.index);
    const card = playerHand[cardIndex];
    const topCard = discardPile[discardPile.length - 1];

    if (
      card.value === CARD_VALUES.WILD_DRAW_FOUR &&
      !canPlayWildDrawFour(playerHand, topCard)
    ) {
      showMessage("You can't play Wild +4, you have other playable cards!");
      return;
    }

    if (isMoveValid(card, topCard)) {
      playCard(card, playerHand, cardIndex, true);
    } else {
      showMessage("You can't play that card!");
    }
  });

  /**
   * Event listener untuk klik pada tumpukan dek untuk mengambil kartu.
   */
  deckElement.addEventListener("click", () => {
    if (currentPlayer !== "player" || isDrawing) return;
    const topCard = discardPile[discardPile.length - 1];

    if (playerHand.some((card) => isMoveValid(card, topCard))) {
      showMessage("You have a playable card!");
      return;
    }

    isDrawing = true;
    deckElement.style.pointerEvents = "none"; // Nonaktifkan deck

    showMessage("You draw a card.");
    drawCards(playerHand, 1);
    renderHand(playerHand, playerHandContainer, true);

    setTimeout(() => {
      const newCard = playerHand[playerHand.length - 1];
      if (isMoveValid(newCard, topCard)) {
        showMessage("You drew a playable card! Play it or Pass.");
        canPass = true;
        passButton.classList.remove("hidden");
      } else {
        showMessage("Card cannot be played. Turn passes to Bot.");
        setTimeout(switchTurn, 1500);
      }
      isDrawing = false;
      deckElement.style.pointerEvents = "auto"; // Aktifkan kembali deck
    }, 500);
  });

  /**
   * Event listener untuk tombol "Pass Turn".
   */
  passButton.addEventListener("click", () => {
    if (currentPlayer === "player" && canPass) {
      canPass = false;
      passButton.classList.add("hidden");
      showMessage("You passed your turn.");
      switchTurn();
    }
  });

  /**
   * Event listener untuk tombol "UNO!".
   */
  unoButton.addEventListener("click", () => {
    if (
      currentPlayer === "player" &&
      playerHand.length === 1 &&
      !unoCalled.player
    ) {
      showMessage("UNO called!");
      unoCalled.player = true;
      clearTimeout(unoTimeout);
      unoButton.classList.add("hidden");
    }
  });

  /**
   * Event listener untuk tombol "Call UNO on Bot!".
   */
  callUnoButton.addEventListener("click", () => {
    if (botMustCallUno) {
      showMessage("You caught the Bot! They draw 2 cards.");
      drawCards(botHand, 2);
      renderHand(botHand, botHandContainer, false);
      botMustCallUno = false;
      unoCalled.bot = true;
      clearTimeout(botUnoPenaltyTimeout);
      callUnoButton.classList.add("hidden");
    }
  });

  /**
   * Event listener untuk pilihan warna setelah memainkan kartu Wild.
   */
  colorChoices.forEach((choice) => {
    choice.addEventListener("click", () => {
      const selectedColor = choice.dataset.color;
      colorPickerModal.classList.remove("visible");
      setWildColor(selectedColor, true);
    });
  });

  /**
   * Event listener untuk tombol "Mulai Ulang" pada modal Game Over.
   */
  restartGameButton.addEventListener("click", () => {
    balance = 5000;
    updateBalance();
    gameOverModal.classList.remove("visible");
    startRoundModal.classList.add("visible");
  });

  /**
   * Event listener untuk tombol "Mulai Bermain" pada modal awal.
   */
  startGameButton.addEventListener("click", () => {
    let betValue = parseInt(initialBetAmountInput.value);

    if (isNaN(betValue) || betValue < 100) {
      startModalMessage.textContent = "Taruhan minimal adalah $100.";
      return;
    }
    if (betValue > balance) {
      startModalMessage.textContent = "Saldo Anda tidak mencukupi!";
      return;
    }

    currentBet = betValue;
    betAmountInput.value = currentBet;
    startRoundModal.classList.remove("visible");
    startRound();
  });

  // =================================================================================
  // FUNGSI UTAMA MEMULAI PERMAINAN/RONDE
  // =================================================================================

  /**
   * Memulai sebuah ronde baru permainan UNO.
   * Mengatur ulang dek, tangan pemain, tumpukan buangan, dan status permainan lainnya.
   */
  function startRound() {
    showMessage(`Ronde dimulai! Taruhan Anda: $${currentBet}.`);
    deck = shuffleDeck(createDeck());
    playerHand = [];
    botHand = [];
    discardPile = [];
    unoCalled = { player: false, bot: false };
    botMustCallUno = false;
    if (botUnoPenaltyTimeout) clearTimeout(botUnoPenaltyTimeout);
    callUnoButton.classList.add("hidden");
    passButton.classList.add("hidden");

    drawCards(playerHand, 7);
    drawCards(botHand, 7);

    let firstCard;
    do {
      if (deck.length === 0) deck = shuffleDeck(createDeck());
      firstCard = deck.pop();
    } while (firstCard.color === CARD_VALUES.WILD);
    discardPile.push(firstCard);

    renderHand(playerHand, playerHandContainer, true);
    renderHand(botHand, botHandContainer, false);
    renderDiscardPile();
    updateBalance();

    currentPlayer = "player";
    updateTurnIndicator();
  }

  // Inisialisasi awal
  updateBalance();
});
