document.addEventListener('DOMContentLoaded', () => {
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

    const gameState = {
        deck: [],
        playerHand: [],
        botHand: [],
        discardPile: [],
        currentPlayer: 'player',
        balance: 5000,
        currentBet: 0,
        playerUnoTimer: null,
        isPlayerUnoCalled: false,
        botHasCalledUno: false,
        playerCallUnoTimer: null,
    };

    function updateView(viewName) {
        uiElements.backgroundMedia.classList.add('hidden');
        uiElements.gameBackgroundMedia.classList.add('hidden');
        uiElements.gameContainer.classList.add('opacity-0');
        uiElements.startModal.classList.add('hidden');
        uiElements.gameOverModal.classList.add('hidden');
        uiElements.colorPickerModal.classList.add('hidden');

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
                uiElements.gameBackgroundMedia.classList.remove('hidden');
                uiElements.gameContainer.classList.remove('opacity-0');
                uiElements.colorPickerModal.classList.remove('hidden');
                break;
        }
    }

    function createDeck() {
        const colors = ['red', 'green', 'blue', 'yellow'];
        const values = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'skip', 'reverse', 'drawTwo'];
        const wildCards = ['wild', 'wildDrawFour'];
        let newDeck = [];

        for (const color of colors) {
            for (const value of values) {
                newDeck.push({ color, value });
                if (value !== '0') newDeck.push({ color, value });
            }
        }

        for (let i = 0; i < 4; i++) {
            for (const wild of wildCards) {
                newDeck.push({ color: 'black', value: wild });
            }
        }
        return newDeck;
    }

    function shuffle(deck) {
        for (let i = deck.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [deck[i], deck[j]] = [deck[j], deck[i]];
        }
    }

    function startGame() {
        const betAmount = parseInt(uiElements.betInput.value);
        if (isNaN(betAmount) || betAmount < 100 || betAmount > gameState.balance) {
            alert('Nilai taruhan tidak valid! Pastikan taruhan minimal $100 dan tidak melebihi saldo Anda.');
            return;
        }
        gameState.currentBet = betAmount;
        gameState.balance -= gameState.currentBet;

        updateView('gameScreen');

        gameState.deck = createDeck();
        shuffle(gameState.deck);
        gameState.playerHand = [];
        gameState.botHand = [];
        gameState.discardPile = [];
        gameState.currentPlayer = 'player';
        gameState.isPlayerUnoCalled = false;
        gameState.botHasCalledUno = false;
        clearTimeout(gameState.playerUnoTimer);
        clearTimeout(gameState.playerCallUnoTimer);
        uiElements.callUnoOnBotButton.classList.add('hidden');

        for (let i = 0; i < 7; i++) {
            drawCards(gameState.playerHand, 1);
            drawCards(gameState.botHand, 1);
        }

        let firstCard = gameState.deck.pop();
        while (firstCard.color === 'black') {
            gameState.deck.push(firstCard);
            shuffle(gameState.deck);
            firstCard = gameState.deck.pop();
        }
        gameState.discardPile.push(firstCard);

        gameState.currentPlayer = 'player';
        renderGame();
    }

    function isCardPlayable(card, topCard) {
        if (card.color === 'black') return true;
        return card.color === topCard.color || card.value === topCard.value;
    }

    function drawCards(hand, amount) {
        for (let i = 0; i < amount; i++) {
            if (gameState.deck.length === 0) {
                const top = gameState.discardPile.pop();
                gameState.deck = [...gameState.discardPile];
                shuffle(gameState.deck);
                gameState.discardPile = [top];
            }
            if (gameState.deck.length > 0) {
                hand.push(gameState.deck.pop());
            }
        }
    }

    function handleCardEffect(card, playedBy) {
        if (checkForWinner()) return;

        let nextPlayer = (playedBy === 'player') ? 'bot' : 'player';
        const opponentHand = (playedBy === 'player') ? gameState.botHand : gameState.playerHand;

        switch (card.value) {
            case 'skip':
            case 'reverse':
                nextPlayer = playedBy;
                break;
            case 'drawTwo':
                drawCards(opponentHand, 2);
                nextPlayer = playedBy;
                break;
            case 'wild':
                if (playedBy === 'player') {
                    updateView('colorPicker');
                    return;
                } else {
                    const colors = ['red', 'green', 'blue', 'yellow'];
                    const chosenColor = colors[Math.floor(Math.random() * colors.length)];
                    gameState.discardPile[gameState.discardPile.length - 1].color = chosenColor;
                }
                break;
            case 'wildDrawFour':
                drawCards(opponentHand, 4);
                if (playedBy === 'player') {
                    updateView('colorPicker');
                    return;
                } else {
                    const colors = ['red', 'green', 'blue', 'yellow'];
                    const chosenColor = colors[Math.floor(Math.random() * colors.length)];
                    gameState.discardPile[gameState.discardPile.length - 1].color = chosenColor;
                }
                break;
        }
        switchTurn(nextPlayer);
    }

    function botTurn() {
        const topCard = gameState.discardPile[gameState.discardPile.length - 1];
        let cardToPlayIndex = gameState.botHand.findIndex(card => isCardPlayable(card, topCard));

        if (cardToPlayIndex !== -1 && gameState.botHand[cardToPlayIndex].value === 'wildDrawFour') {
            const hasOtherOptions = gameState.botHand.some(c => c.value !== 'wildDrawFour' && isCardPlayable(c, topCard));
            if (hasOtherOptions) {
                cardToPlayIndex = gameState.botHand.findIndex(card => isCardPlayable(card, topCard) && card.value !== 'wildDrawFour');
            }
        }

        gameState.botHasCalledUno = false;

        if (cardToPlayIndex !== -1) {
            const card = gameState.botHand.splice(cardToPlayIndex, 1)[0];
            gameState.discardPile.push(card);

            if (gameState.botHand.length === 1) {
                if (Math.random() < 0.8) {
                    gameState.botHasCalledUno = true;
                }
                startPlayerCallUnoTimer();
            }

            handleCardEffect(card, 'bot');
        } else {
            drawCards(gameState.botHand, 1);
            const newCard = gameState.botHand[gameState.botHand.length - 1];
            if (newCard && isCardPlayable(newCard, topCard)) {
                const card = gameState.botHand.pop();
                gameState.discardPile.push(card);
                handleCardEffect(card, 'bot');
            } else {
                switchTurn('player');
            }
        }
    }

    function switchTurn(nextPlayer) {
        gameState.currentPlayer = nextPlayer;

        if (gameState.currentPlayer === 'bot') {
            uiElements.callUnoOnBotButton.classList.add('hidden');
            clearTimeout(gameState.playerCallUnoTimer);
            renderGame();
            setTimeout(botTurn, 1000);
        } else {
            renderGame();
        }
    }

    function playerPlayCard(cardIndex) {
        if (gameState.currentPlayer !== 'player') return;

        const card = gameState.playerHand[cardIndex];
        const topCard = gameState.discardPile[gameState.discardPile.length - 1];

        if (card.value === 'wildDrawFour') {
            const hasOtherPlayableCard = gameState.playerHand.some(c => c.value !== 'wildDrawFour' && c.color === topCard.color);
            if (hasOtherPlayableCard) {
                alert('Anda tidak bisa memainkan kartu +4 jika masih punya kartu lain dengan warna yang sama!');
                return;
            }
        }

        if (isCardPlayable(card, topCard)) {
            gameState.playerHand.splice(cardIndex, 1);
            gameState.discardPile.push(card);

            if (gameState.playerHand.length === 1) {
                startPlayerUnoTimer();
            } else {
                clearTimeout(gameState.playerUnoTimer);
            }
            gameState.isPlayerUnoCalled = false;

            handleCardEffect(card, 'player');
        } else {
            alert('Kartu tidak valid!');
        }
    }

    function checkForWinner() {
        let winner = null;
        if (gameState.playerHand.length === 0) winner = 'Pemain';
        if (gameState.botHand.length === 0) winner = 'Bot';

        if (winner) {
            clearTimeout(gameState.playerUnoTimer);
            clearTimeout(gameState.playerCallUnoTimer);
            setTimeout(() => {
                if (winner === 'Pemain') {
                    alert('Selamat, Anda memenangkan ronde ini!');
                    gameState.balance += gameState.currentBet * 2;
                } else {
                    alert('Sayang sekali, Bot memenangkan ronde ini.');
                }

                if (gameState.balance <= 0) {
                    updateView('gameOverScreen');
                } else {
                    updateView('startScreen');
                }
                updateInfoBar();
            }, 500);
            return true;
        }
        return false;
    }

    function startPlayerUnoTimer() {
        gameState.playerUnoTimer = setTimeout(() => {
            if (!gameState.isPlayerUnoCalled) {
                alert("Anda lupa menekan UNO! Penalti +2 kartu.");
                drawCards(gameState.playerHand, 2);
                renderGame();
            }
        }, 5000);
    }

    function startPlayerCallUnoTimer() {
        uiElements.callUnoOnBotButton.classList.remove('hidden');
        gameState.playerCallUnoTimer = setTimeout(() => {
            uiElements.callUnoOnBotButton.classList.add('hidden');
        }, 3000);
    }

    function renderGame() {
        renderPlayerHand();
        renderBotHand();
        renderDiscardPile();
        updateInfoBar();
        toggleUnoButton();
    }

    function renderPlayerHand() {
        uiElements.playerHand.innerHTML = '';
        gameState.playerHand.forEach((card, index) => {
            const cardEl = createCardElement(card);
            cardEl.addEventListener('click', () => playerPlayCard(index));
            uiElements.playerHand.appendChild(cardEl);
        });
    }

    function renderBotHand() {
        uiElements.botHand.innerHTML = '';
        gameState.botHand.forEach(() => {
            const cardEl = createCardElement({ back: true });
            uiElements.botHand.appendChild(cardEl);
        });
    }

    function renderDiscardPile() {
        uiElements.discardPile.innerHTML = '';
        const topCard = gameState.discardPile[gameState.discardPile.length - 1];

        const colorClasses = ['ring-red-500', 'ring-green-500', 'ring-blue-500', 'ring-yellow-500', 'ring-gray-700'];
        uiElements.discardPile.classList.remove(...colorClasses, 'ring-4', 'rounded-lg');

        if (topCard) {
            uiElements.discardPile.appendChild(createCardElement(topCard));

            const ringColorMap = {
                red: 'ring-red-500', green: 'ring-green-500', blue: 'ring-blue-500', yellow: 'ring-yellow-500', black: 'ring-gray-700'
            };
            const activeColorClass = ringColorMap[topCard.color];
            if (activeColorClass) {
                uiElements.discardPile.classList.add('ring-4', 'rounded-lg', activeColorClass);
            }
        }
    }

    function updateInfoBar() {
        uiElements.balance.textContent = gameState.balance;
        uiElements.currentBet.textContent = gameState.currentBet;
        uiElements.turnIndicator.textContent = gameState.currentPlayer === 'player' ? 'Anda' : 'Bot';
        uiElements.modalBalance.textContent = gameState.balance;
    }

    function toggleUnoButton() {
        const isVisible = gameState.playerHand.length === 2;
        uiElements.unoButton.classList.toggle('hidden', !isVisible);
    }

    function createCardElement(card) {
        const cardClasses = "w-20 h-28 md:w-24 md:h-36 lg:w-28 lg:h-40 rounded-lg shadow-md transition-all duration-200 hover:-translate-y-2 hover:shadow-xl cursor-pointer";
        const imgEl = document.createElement('img');
        imgEl.className = cardClasses;

        if (card.back) {
            imgEl.src = 'assets/cards/back.png';
            imgEl.alt = 'Kartu UNO belakang';
        } else {
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

    function initializeEventListeners() {
        uiElements.startGameButton.addEventListener('click', startGame);

        uiElements.restartGameButton.addEventListener('click', () => {
            gameState.balance = 5000;
            updateView('startScreen');
            updateInfoBar();
        });

        uiElements.drawPile.addEventListener('click', () => {
            if (gameState.currentPlayer !== 'player') return;
            drawCards(gameState.playerHand, 1);

            const newCard = gameState.playerhand[gameState.playerHand.length - 1];
            const topCard = gameState.discardPile[gameState.discardPile.length - 1];
            if (newCard && !isCardPlayable(newCard, topCard)) {
                setTimeout(() => switchTurn('bot'), 500);
            }
            renderGame();
        });

        document.querySelectorAll('.color-choice').forEach(button => {
            button.addEventListener('click', (e) => {
                const chosenColor = e.target.dataset.color;
                gameState.discardPile[gameState.discardPile.length - 1].color = chosenColor;
                updateView('gameScreen');

                const lastCard = gameState.discardPile[gameState.discardPile.length - 1];
                if (lastCard.value === 'wildDrawFour') {
                    switchTurn('bot');
                } else {
                    switchTurn('bot');
                }
            });
        });

        uiElements.unoButton.addEventListener('click', () => {
            if (gameState.playerHand.length === 1 && gameState.playerUnoTimer) {
                gameState.isPlayerUnoCalled = true;
                clearTimeout(gameState.playerUnoTimer);
                alert("UNO!");
            }
        });

        uiElements.callUnoOnBotButton.addEventListener('click', () => {
            if (gameState.botHand.length === 1 && !gameState.botHasCalledUno) {
                alert("Berhasil! Anda menangkap Bot lupa UNO. Bot mengambil 2 kartu penalti.");
                drawCards(gameState.botHand, 2);
                renderGame();
            } else if (gameState.botHand.length === 1 && gameState.botHasCalledUno) {
                alert("Gagal! Bot sudah memanggil UNO.");
            }
            uiElements.callUnoOnBotButton.classList.add('hidden');
            clearTimeout(gameState.playerCallUnoTimer);
        });
    }

    function init() {
        initializeEventListeners();
        updateView('startScreen');
        updateInfoBar();
    }

    init();
});