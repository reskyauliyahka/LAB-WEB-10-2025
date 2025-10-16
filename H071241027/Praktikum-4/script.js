// script.js

// --- Data Kartu ---
const colors = ["red", "blue", "green", "yellow"];
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
  "skip",
  "reverse",
  "plus2",
];

function createDeck() {
  let deck = [];
  // Kartu Angka (0-9)
  for (const color of colors) {
    // 0 ada 1 kartu
    deck.push({ color, value: "0", image: `${color}_0.png` });
    // 1-9 ada 2 kartu
    for (let i = 1; i <= 9; i++) {
      deck.push({ color, value: `${i}`, image: `${color}_${i}.png` });
      deck.push({ color, value: `${i}`, image: `${color}_${i}.png` });
    }
  }
  // kartu Aksi
  for (const color of colors) {
    deck.push({ color, value: "skip", image: `${color}_skip.png` });
    deck.push({ color, value: "skip", image: `${color}_skip.png` });
    deck.push({ color, value: "reverse", image: `${color}_reverse.png` });
    deck.push({ color, value: "reverse", image: `${color}_reverse.png` });
    deck.push({ color, value: "plus2", image: `${color}_plus2.png` });
    deck.push({ color, value: "plus2", image: `${color}_plus2.png` });
  }
  // Kartu Wild (masing-masing 4)
  for (let i = 0; i < 4; i++) {
    deck.push({ color: "wild", value: "wild", image: "wild.png" });
    deck.push({ color: "wild", value: "plus4", image: "plus_4.png" });
  }
  return deck;
}

// --- State Permainan ---
let gameDeck = [];
let playerHand = [];
let botHand = [];
let discardPile = [];
let currentPlayer = "player";
let playerBalance = 5000;
let currentBet = 0;
let isBettingPhase = true;
let unoButtonTimer = null;
let unoButtonActive = false;
let gameDirection = 1; // 1 = player -> bot, -1 = bot -> player
let isCardPlayedThisTurn = false;

// --- Elemen DOM ---
const playerHandEl = document.getElementById("player-hand");
const botHandEl = document.getElementById("bot-hand");
const deckEl = document.getElementById("deck");
const discardPileEl = document.getElementById("discard-pile");
const gameStatusEl = document.getElementById("game-status");
const unoButtonEl = document.getElementById("uno-button");
const unoButtonContainerEl = document.querySelector(".uno-button-container");
const playerCardCountEl = document.getElementById("player-card-count");
const botCardCountEl = document.getElementById("bot-card-count");
const playerBalanceDisplayEl = document.getElementById("player-balance-display");
const currentBetDisplayEl = document.getElementById("current-bet-display");
const bettingAreaEl = document.getElementById("betting-area");
const betInputEl = document.getElementById("bet-input");
const placeBetButtonEl = document.getElementById("place-bet-button");
const wildColorsEl = document.getElementById("wild-colors");
const gameOverModalEl = document.getElementById("game-over-modal");
const gameOverTitleEl = document.getElementById("game-over-title");
const gameOverMessageEl = document.getElementById("game-over-message");
const restartButtonEl = document.getElementById("restart-button");

// --- Fungsi Helper ---
function shuffleDeck(deck) {
  for (let i = deck.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [deck[i], deck[j]] = [deck[j], deck[i]];
  }
}

function dealCards() {
  playerHand = [];
  botHand = [];
  for (let i = 0; i < 7; i++) {
    playerHand.push(gameDeck.pop());
    botHand.push(gameDeck.pop());
  }
  // Satu kartu untuk discard pile
  let startCard = gameDeck.pop();
  // Pastikan kartu pertama bukan kartu aksi atau wild
  while (
    startCard.value === "skip" ||
    startCard.value === "reverse" ||
    startCard.value === "plus2" ||
    startCard.color === "wild" ||
    startCard.value === "plus4"
  ) {
    gameDeck.push(startCard);
    shuffleDeck(gameDeck);
    startCard = gameDeck.pop();
  }
  discardPile.push(startCard);
}

function updateUI() {
  renderHands();
  const topCard = discardPile[discardPile.length - 1];
  discardPileEl.style.backgroundImage = `url('kartu/${topCard.image}')`;
  // Update warna border discard pile untuk kartu wild
  discardPileEl.parentElement.style.borderColor =
    topCard.color === "wild" ? "#FFF" : "transparent";
  discardPileEl.parentElement.style.borderWidth = "2px";
  discardPileEl.parentElement.style.borderStyle = "solid";

  playerCardCountEl.textContent = playerHand.length;
  botCardCountEl.textContent = botHand.length;
  playerBalanceDisplayEl.textContent = `Saldo: $${playerBalance}`;
  currentBetDisplayEl.textContent = currentBet;
}

function renderHands() {
  // Render tangan pemain
  playerHandEl.innerHTML = "";
  playerHand.forEach((card) => {
    const cardEl = document.createElement("div");
    cardEl.classList.add("card", "card-player");
    cardEl.style.backgroundImage = `url('kartu/${card.image}')`;
    cardEl.dataset.color = card.color;
    cardEl.dataset.value = card.value;
    playerHandEl.appendChild(cardEl);
  });

  // Render tangan bot (hanya kartu belakang)
  botHandEl.innerHTML = "";
  botHand.forEach((card) => {
    const cardEl = document.createElement("div");
    cardEl.classList.add("card", "card-bot");
    cardEl.style.backgroundImage = `url('kartu/card_back.png')`;
    botHandEl.appendChild(cardEl);
  });
}

function canPlayCard(card) {
  const topCard = discardPile[discardPile.length - 1];
  // Wild atau Wild Draw Four bisa dimainkan kapan saja
  if (card.color === "wild") {
    return true;
  }
  // Jika tidak wild, harus cocok warna atau nilai
  return card.color === topCard.color || card.value === topCard.value;
}

function checkWin() {
  if (playerHand.length === 0) {
    gameOver(true);
    return true;
  }
  if (botHand.length === 0) {
    gameOver(false);
    return true;
  }
  return false;
}

function gameOver(isPlayerWinner) {
  isBettingPhase = true;
  bettingAreaEl.style.display = "flex";
  if (isPlayerWinner) {
    playerBalance += currentBet;
    gameOverTitleEl.textContent = "Anda Menang!";
    gameOverMessageEl.textContent = `Selamat, Anda memenangkan $${currentBet}!`;
  } else {
    playerBalance -= currentBet;
    gameOverTitleEl.textContent = "Anda Kalah!";
    gameOverMessageEl.textContent = `Anda kalah $${currentBet}.`;
  }

  if (playerBalance <= 0) {
    gameOverTitleEl.textContent = "Game Over";
    gameOverMessageEl.textContent =
      "Saldo Anda habis. Mari coba lagi dari awal.";
    restartButtonEl.style.display = "block";
  } else {
    restartButtonEl.style.display = "none";
  }

  currentBet = 0;
  gameOverModalEl.classList.remove("hidden");
  unoButtonContainerEl.style.display = "none";
  updateUI();
}

function switchTurn() {
  currentPlayer =
    gameDirection === 1
      ? currentPlayer === "player"
        ? "bot"
        : "player"
      : currentPlayer === "player"
      ? "bot"
      : "player";

  // Perbaikan utama: reset status di setiap pergantian giliran
  isCardPlayedThisTurn = false;
  gameStatusEl.textContent =
    currentPlayer === "player" ? "Giliran Anda." : "Giliran Bot.";

  if (currentPlayer === "bot") {
    setTimeout(handleBotTurn, 1500);
  }
}

function handleCardPlay(card, player) {
  const hand = player === "player" ? playerHand : botHand;
  const index = hand.findIndex(
    (c) => c.color === card.color && c.value === card.value
  );

  if (index > -1) {
    const playedCard = hand.splice(index, 1)[0];
    discardPile.push(playedCard);
    isCardPlayedThisTurn = true;

    // Cek kartu UNO
    if (player === "player" && playerHand.length === 1) {
      startUnoTimer();
    } else {
      clearUnoTimer();
    }

    updateUI();

    // Logika kartu aksi
    let skipTurn = false;

    if (playedCard.value === "skip") {
      gameStatusEl.textContent = `Kartu Skip! ${
        player === "player" ? "Bot" : "Anda"
      } dilewati.`;
      skipTurn = true;
    } else if (playedCard.value === "reverse") {
      gameDirection *= -1;
      gameStatusEl.textContent = "Arah permainan dibalik.";
    } else if (playedCard.value === "plus2") {
      gameStatusEl.textContent = `Kartu +2! ${
        player === "player" ? "Bot" : "Anda"
      } mengambil 2 kartu.`;
      const targetHand = player === "player" ? botHand : playerHand;
      drawCards(targetHand, 2);
      skipTurn = true;
    } else if (playedCard.color === "wild") {
      if (player === "player") {
        showWildColorPicker(playedCard);
        return; // Tunggu pemain memilih warna
      } else {
        const newColor = colors[Math.floor(Math.random() * colors.length)];
        discardPile[discardPile.length - 1].color = newColor;
        gameStatusEl.textContent = `Bot memainkan Wild. Warna berubah menjadi ${newColor.toUpperCase()}.`;
        if (playedCard.value === "plus4") {
          gameStatusEl.textContent += " Anda mengambil 4 kartu.";
          drawCards(playerHand, 4);
        }
      }
    }

    if (checkWin()) return;

    if (skipTurn) {
      switchTurn(); // Pindah giliran dua kali
    } else {
      switchTurn();
    }
  }
}

function drawCards(hand, num) {
  for (let i = 0; i < num; i++) {
    if (gameDeck.length === 0) {
      const topCard = discardPile.pop();
      gameDeck = [...discardPile];
      shuffleDeck(gameDeck);
      discardPile = [topCard];
    }
    hand.push(gameDeck.pop());
  }
  updateUI();
}

function handleBotTurn() {
  const topCard = discardPile[discardPile.length - 1];
  let cardToPlay = null;

  // Strategi bot:
  const playableCards = botHand.filter((card) => canPlayCard(card));
  const actionCards = playableCards.filter(
    (card) =>
      card.value === "plus2" ||
      card.value === "skip" ||
      card.value === "reverse"
  );

  // Logika utama: mainkan kartu aksi, lalu kartu angka, lalu Wild
  if (actionCards.length > 0) {
    cardToPlay = actionCards[0];
  } else if (playableCards.length > 0) {
    cardToPlay = playableCards[0];
  } else {
    const wildPlus4Card = botHand.find((card) => card.value === "plus4");
    const otherPlayableCards = botHand.filter(
      (c) => canPlayCard(c) && c.value !== "plus4"
    );
    if (wildPlus4Card && otherPlayableCards.length === 0) {
      cardToPlay = wildPlus4Card;
    }
  }

  if (cardToPlay) {
    gameStatusEl.textContent = `Bot memainkan kartu.`;
    handleCardPlay(cardToPlay, "bot");
  } else {
    drawCards(botHand, 1);
    gameStatusEl.textContent = "Bot mengambil kartu.";
    // Jika bot mengambil kartu dan tidak bisa bermain, giliran pindah
    setTimeout(switchTurn, 1000);
  }

  if (botHand.length === 1) {
    setTimeout(() => {
      gameStatusEl.textContent = "Bot teriak UNO!";
      unoButtonContainerEl.style.display = "none";
    }, 500);
  }
}

function showWildColorPicker(card) {
  wildColorsEl.style.display = "flex";
  wildColorsEl.querySelectorAll("div").forEach((picker) => {
    picker.onclick = () => {
      const newColor = picker.dataset.color;
      discardPile[discardPile.length - 1].color = newColor;
      wildColorsEl.style.display = "none";
      gameStatusEl.textContent = `Warna berubah menjadi ${newColor.toUpperCase()}.`;
      if (card.value === "plus4") {
        gameStatusEl.textContent += " Bot mengambil 4 kartu.";
        drawCards(botHand, 4);
      }
      if (checkWin()) return;
      switchTurn();
      updateUI();
    };
  });
}

function startUnoTimer() {
  unoButtonActive = true;
  unoButtonContainerEl.style.display = "block";
  gameStatusEl.textContent = "UNO! Tekan tombol UNO dalam 5 detik!";
  unoButtonTimer = setTimeout(() => {
    if (unoButtonActive) {
      gameStatusEl.textContent = "Terlambat! Penalti +2 kartu.";
      drawCards(playerHand, 2);
      unoButtonActive = false;
      unoButtonContainerEl.style.display = "none";
    }
  }, 5000);
}

function clearUnoTimer() {
  clearTimeout(unoButtonTimer);
  unoButtonActive = false;
  unoButtonContainerEl.style.display = "none";
}

// --- Event Listeners ---
placeBetButtonEl.addEventListener("click", () => {
  const bet = parseInt(betInputEl.value);
  if (isNaN(bet) || bet < 100 || bet > playerBalance) {
    alert("Taruhan tidak valid. Minimal $100 dan tidak melebihi saldo.");
    return;
  }
  currentBet = bet;
  isBettingPhase = false;
  bettingAreaEl.style.display = "none";
  startGame();
});

deckEl.addEventListener("click", () => {
  if (currentPlayer === "player" && !isCardPlayedThisTurn) {
    drawCards(playerHand, 1);
    gameStatusEl.textContent = "Anda mengambil kartu.";
    isCardPlayedThisTurn = true; // Tandai pemain telah mengambil kartu
    setTimeout(switchTurn, 1000);
  }
});

playerHandEl.addEventListener("click", (event) => {
  if (currentPlayer !== "player") return;
  const cardEl = event.target.closest(".card");
  if (!cardEl) return;

  const card = {
    color: cardEl.dataset.color,
    value: cardEl.dataset.value,
  };

  if (card.value === "plus4") {
    const hasOtherPlayable = playerHand.some(
      (c) => canPlayCard(c) && c.value !== "plus4"
    );
    if (hasOtherPlayable) {
      gameStatusEl.textContent =
        "Anda memiliki kartu lain yang bisa dimainkan, tidak boleh menggunakan +4.";
      return;
    }
  }

  if (canPlayCard(card)) {
    handleCardPlay(card, "player");
  } else {
    gameStatusEl.textContent =
      "Kartu tidak cocok. Mainkan kartu lain atau ambil dari deck.";
  }
});

unoButtonEl.addEventListener("click", () => {
  if (playerHand.length === 1 && unoButtonActive) {
    clearUnoTimer();
    unoButtonActive = false;
    gameStatusEl.textContent = "UNO! Anda aman.";
  }
});

restartButtonEl.addEventListener("click", () => {
  playerBalance = 5000;
  gameOverModalEl.classList.add("hidden");
  bettingAreaEl.style.display = "flex";
  updateUI();
});

// --- Inisialisasi Game ---
function startGame() {
  gameDeck = createDeck();
  shuffleDeck(gameDeck);
  dealCards();
  currentPlayer = "player";
  gameStatusEl.textContent = "Giliran Anda. Mainkan kartu pertama.";
  isCardPlayedThisTurn = false;
  updateUI();
}

// Mulai game dengan fase taruhan
updateUI();
