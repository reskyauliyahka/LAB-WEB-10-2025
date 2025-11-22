
const colors = ['red', 'blue', 'green', 'yellow'];
const values = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'Skip', 'Reverse', '+2'];

let deck = [];
let playerHand = [];
let botHand = [];
let discardPile = [];
let currentPlayer = 'player';
let balance = 5000;
let currentBet = 0;
let unoPressed = { player: false, bot: false };
let unoTimer = null;
let gameActive = false;
let pendingWildCard = null;


function createDeck() {
  deck = [];
  colors.forEach(color => {
    values.forEach(value => {
      deck.push({ color, value });
      if (value !== '0') deck.push({ color, value });
    });
  });

  for (let i = 0; i < 4; i++) {
    deck.push({ color: 'wild', value: 'Wild' });
    deck.push({ color: 'wild', value: '+4' });
  }

  shuffleDeck();
}

function shuffleDeck() {
  for (let i = deck.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [deck[i], deck[j]] = [deck[j], deck[i]];
  }
}

function drawCardFromDeck() {
  if (deck.length === 0) {
    const topCard = discardPile.pop();
    deck = [...discardPile];
    discardPile = [topCard];
    shuffleDeck();
  }
  return deck.pop();
}

function startGame() {
  const betInput = document.getElementById('bet-input');
  const bet = parseInt(betInput.value);

  if (!bet || bet < 100 || bet > balance) {
  const modal = document.getElementById('bet-modal');
  let msg = modal.querySelector('.error-message');

  // Create error message element if it doesn't exist
  if (!msg) {
    msg = document.createElement('div');
    msg.className = 'error-message';
    modal.querySelector('.modal-content').appendChild(msg);
  }

  msg.textContent = '⚠️ Taruhan tidak valid! Minimal $100 dan maksimal sesuai saldo.';
  msg.style.display = 'block';

  // Auto-hide after 3 seconds
  setTimeout(() => { msg.style.display = 'none'; }, 3000);

  return;
}


  currentBet = bet;
  document.getElementById('bet-modal').classList.remove('active');

  createDeck();
  playerHand = [];
  botHand = [];
  discardPile = [];

  for (let i = 0; i < 7; i++) {
    playerHand.push(drawCardFromDeck());
    botHand.push(drawCardFromDeck());
  }

  let firstCard = drawCardFromDeck();
  while (firstCard.color === 'wild') {
    deck.unshift(firstCard);
    shuffleDeck();
    firstCard = drawCardFromDeck();
  }
  discardPile.push(firstCard);

  currentPlayer = 'player';
  unoPressed = { player: false, bot: false };
  gameActive = true;

  updateDisplay();
  updateStatus('Giliran Anda! Mainkan kartu atau ambil dari deck.');
}

function canPlayCard(card, topCard) {
  if (card.color === 'wild') return true;
  if (card.color === topCard.color) return true;
  if (card.value === topCard.value) return true;
  return false;
}

function canPlayWildDrawFour() {
  const topCard = discardPile[discardPile.length - 1];
  return !playerHand.some(card => 
    card.color !== 'wild' && canPlayCard(card, topCard)
  );
}

function playCard(cardIndex) {
  if (!gameActive || currentPlayer !== 'player') return;

  const card = playerHand[cardIndex];
  const topCard = discardPile[discardPile.length - 1];

  if (!canPlayCard(card, topCard)) {
    updateStatus('Kartu tidak bisa dimainkan!');
    return;
  }

  if (card.value === '+4' && !canPlayWildDrawFour()) {
    updateStatus('Wild +4 hanya boleh dimainkan jika tidak ada kartu lain yang bisa dimainkan!');
    return;
  }

  playerHand.splice(cardIndex, 1);
  discardPile.push(card);

  if (playerHand.length === 1) startUnoTimer('player');
  if (playerHand.length === 0) { endGame(true); return; }

  if (card.color === 'wild') {
    pendingWildCard = card;
    document.getElementById('color-modal').classList.add('active');
  } else {
    handleCardAction(card, 'player');
  }

  updateDisplay();
}

// Ganti: function handleCardAction(card) { ... }
function handleCardAction(card, playedBy) {
  const opponent = playedBy === 'player' ? 'bot' : 'player';

  // ========== SKIP / REVERSE ==========
  if (card.value === 'Skip' || card.value === 'Reverse') {
    if (playedBy === 'player') {
      updateStatus('Bot dilewati! Giliran Anda lagi.');
      setTimeout(() => {
        if (!gameActive) return;
        currentPlayer = 'player';
        updateStatus('Giliran Anda lagi!');
      }, 800);
    } else {
      updateStatus('Anda dilewati! Bot main lagi.');
      setTimeout(() => {
        if (!gameActive) return;
        currentPlayer = 'bot';
        updateStatus('Giliran Bot lagi...');
        setTimeout(botTurn, 1000);
      }, 800);
    }
  }

  // ========== +2 ==========
  else if (card.value === '+2') {
    for (let i = 0; i < 2; i++) {
      if (opponent === 'bot') botHand.push(drawCardFromDeck());
      else playerHand.push(drawCardFromDeck());
    }

    updateStatus(playedBy === 'player' ? 'Bot mengambil 2 kartu!' : 'Anda mengambil 2 kartu!');
    updateDisplay();

    setTimeout(() => {
      if (!gameActive) return;
      currentPlayer = playedBy; // pemain yang main tetap dapat giliran lagi
      if (playedBy === 'bot') {
        updateStatus('Giliran Bot lagi...');
        setTimeout(botTurn, 1000);
      } else {
        updateStatus('Giliran Anda lagi!');
      }
    }, 1200);
  }

  // ========== +4 ==========
  else if (card.value === '+4') {
    for (let i = 0; i < 4; i++) {
      if (opponent === 'bot') botHand.push(drawCardFromDeck());
      else playerHand.push(drawCardFromDeck());
    }

    updateStatus(playedBy === 'player' ? 'Bot mengambil 4 kartu!' : 'Anda mengambil 4 kartu!');
    updateDisplay();

    setTimeout(() => {
      if (!gameActive) return;
      currentPlayer = playedBy; // sama: tetap giliran dia
      if (playedBy === 'bot') {
        updateStatus('Giliran Bot lagi...');
        setTimeout(botTurn, 1000);
      } else {
        updateStatus('Giliran Anda lagi!');
      }
    }, 1500);
  }

  // ========== KARTU BIASA ==========
  else {
    currentPlayer = opponent;
    if (opponent === 'bot') {
      setTimeout(botTurn, 1000);
    } else {
      updateStatus('Giliran Anda!');
    }
  }

  updateDisplay();
}



function selectColor(color) {
  if (pendingWildCard) {
    const card = pendingWildCard;
    card.color = color;
    discardPile[discardPile.length - 1] = card;
    pendingWildCard = null;

    // Close modal and immediately update visuals
    document.getElementById('color-modal').classList.remove('active');
    updateDisplay();

    // Announce the chosen color
    const colorNames = { red: 'Merah ❤️', blue: 'Biru 💙', green: 'Hijau 💚', yellow: 'Kuning 💛' };
    updateStatus(`Warna dipilih: ${colorNames[color] || color}.`);

    // Add a small delay so players see the color choice before continuing
    setTimeout(() => {
      handleCardAction(card, 'player');
    }, 800);
  }
}


function drawCard() {
  if (!gameActive || currentPlayer !== 'player') return;

  const card = drawCardFromDeck();
  playerHand.push(card);
  updateDisplay();

  const topCard = discardPile[discardPile.length - 1];

  // Cek apakah kartu yang diambil bisa langsung dimainkan
  if (canPlayCard(card, topCard)) {
    updateStatus('Anda mengambil kartu yang bisa dimainkan! Mau mainkan atau lewati giliran?');

    // Tampilkan tombol pilihan
    const statusBar = document.getElementById('status-bar');
    const playBtn = document.createElement('button');
    const skipBtn = document.createElement('button');

    playBtn.textContent = 'Mainkan Kartu';
    skipBtn.textContent = 'Lewati Giliran';

    playBtn.style.marginLeft = '10px';
    skipBtn.style.marginLeft = '10px';
    playBtn.style.padding = skipBtn.style.padding = '8px 14px';
    playBtn.style.borderRadius = skipBtn.style.borderRadius = '8px';
    playBtn.style.border = skipBtn.style.border = 'none';
    playBtn.style.cursor = skipBtn.style.cursor = 'pointer';
    playBtn.style.background = 'linear-gradient(135deg, #00c853, #64dd17)';
    skipBtn.style.background = 'linear-gradient(135deg, #ff5252, #e53935)';
    playBtn.style.color = skipBtn.style.color = 'white';
    playBtn.style.fontWeight = skipBtn.style.fontWeight = 'bold';

    statusBar.appendChild(playBtn);
    statusBar.appendChild(skipBtn);

    // Aksi jika pemain memilih mainkan
    playBtn.onclick = () => {
      const cardIndex = playerHand.indexOf(card);
      if (cardIndex !== -1) playCard(cardIndex);
      playBtn.remove();
      skipBtn.remove();
    };

    // Aksi jika pemain memilih lewati
    skipBtn.onclick = () => {
      playBtn.remove();
      skipBtn.remove();
      updateStatus('Anda melewati giliran.');
      setTimeout(() => {
        currentPlayer = 'bot';
        botTurn();
      }, 1000);
    };
  } else {
    // Jika kartu tidak bisa dimainkan
    updateStatus('Anda mengambil kartu dari deck dan tidak bisa dimainkan.');
    setTimeout(() => {
      currentPlayer = 'bot';
      botTurn();
    }, 1000);
  }
}


function botTurn() {
  if (!gameActive || currentPlayer !== 'bot') return;
  updateStatus('Giliran Bot...');

  setTimeout(() => {
    const topCard = discardPile[discardPile.length - 1];
    let playable = botHand.map((card, i) => ({ card, i }))
      .filter(({ card }) => canPlayCard(card, topCard));

    playable = playable.filter(({ card }) =>
      card.value !== '+4' || !botHand.some(c => c.color !== 'wild' && canPlayCard(c, topCard))
    );

    if (playable.length > 0) {
      const { card, i } = playable[0];
      botHand.splice(i, 1);

      if (card.color === 'wild') {
        const counts = { red: 0, blue: 0, green: 0, yellow: 0 };
        botHand.forEach(c => { if (c.color !== 'wild') counts[c.color]++; });
        card.color = Object.keys(counts).reduce((a, b) => counts[a] > counts[b] ? a : b);
      }

      discardPile.push(card);

      // --- if bot now has 1 card, start UNO timer and auto-press UNO ---
      if (botHand.length === 1) {
        startUnoTimer('bot');

        // simulate bot pressing UNO shortly (random small delay)
        const pressDelay = Math.floor(Math.random() * 800) + 300; // 300-1100ms
        setTimeout(() => {
          if (!gameActive) return;
          unoPressed.bot = true;
          const timerEl = document.getElementById('uno-timer');
          if (unoTimer) { clearInterval(unoTimer); unoTimer = null; timerEl.classList.remove('active'); }
          updateStatus('Bot: UNO!');
          updateDisplay();
        }, pressDelay);
      }

      if (botHand.length === 0) { endGame(false); return; }

      updateStatus(`Bot memainkan ${card.value} ${card.color}`);
      handleCardAction(card, 'bot');
    } else {
      // bot can't play -> draw
      botHand.push(drawCardFromDeck());
      updateStatus('Bot mengambil kartu dari deck.');

      // if the draw made bot have 1 card, do same UNO behavior
      if (botHand.length === 1) {
        startUnoTimer('bot');

        const pressDelay = Math.floor(Math.random() * 800) + 300;
        setTimeout(() => {
          if (!gameActive) return;
          unoPressed.bot = true;
          const timerEl = document.getElementById('uno-timer');
          if (unoTimer) { clearInterval(unoTimer); unoTimer = null; timerEl.classList.remove('active'); }
          updateStatus('Bot: UNO!');
          updateDisplay();
        }, pressDelay);
      }

      setTimeout(() => {
        currentPlayer = 'player';
        updateStatus('Giliran Anda!');
      }, 1000);
    }

    updateDisplay();
  }, 1000);
}


// ========== UNO TIMER & STATUS ==========

function startUnoTimer(player) {
  const timerEl = document.getElementById('uno-timer');
  const unoButton = document.getElementById('uno-button');
  if (player === 'player') unoButton.disabled = false;

  let timeLeft = 5;
  timerEl.textContent = timeLeft;
  timerEl.classList.add('active');

  if (unoTimer) clearInterval(unoTimer);
  unoTimer = setInterval(() => {
    timeLeft--;
    timerEl.textContent = timeLeft;

    if (timeLeft <= 0) {
      clearInterval(unoTimer);
      timerEl.classList.remove('active');

      if (player === 'player' && !unoPressed.player) {
        playerHand.push(drawCardFromDeck(), drawCardFromDeck());
        updateStatus('Anda lupa tekan UNO! Penalti +2 kartu.');
        unoButton.disabled = true;
      } else if (player === 'bot' && !unoPressed.bot) {
        botHand.push(drawCardFromDeck(), drawCardFromDeck());
        updateStatus('Bot lupa tekan UNO! Penalti +2 kartu.');
      }
      updateDisplay();
    }
  }, 1000);
}

function callUno() {
  if (playerHand.length === 1) {
    unoPressed.player = true;
    updateStatus('UNO! Anda punya 1 kartu tersisa!');
    document.getElementById('uno-button').disabled = true;
    if (unoTimer) {
      clearInterval(unoTimer);
      document.getElementById('uno-timer').classList.remove('active');
    }
  }
}

// ========== ENDGAME & RESET ==========

function endGame(playerWon) {
  gameActive = false;
  const modal = document.getElementById('gameover-modal');
  const title = document.getElementById('gameover-title');
  const msg = document.getElementById('gameover-message');

  if (playerWon) {
    balance += currentBet;
    title.textContent = '🎉 ANDA MENANG! 🎉';
    msg.textContent = `Selamat! Anda memenangkan $${currentBet}. Saldo baru: $${balance}`;
  } else {
    balance -= currentBet;
    title.textContent = '😢 ANDA KALAH';
    msg.textContent = `Bot menang! Anda kehilangan $${currentBet}. Saldo: $${balance}`;
  }

  document.getElementById('balance').textContent = balance;

  if (balance < 100) {
    title.textContent = '💸 GAME OVER 💸';
    msg.textContent = 'Saldo Anda habis! Game akan direset.';
  }

  modal.classList.add('active');
}

function resetGame() {
  if (balance < 100) {
    balance = 5000;
    document.getElementById('balance').textContent = balance;
  }

  document.getElementById('gameover-modal').classList.remove('active');
  document.getElementById('bet-modal').classList.add('active');
  document.getElementById('modal-balance').textContent = balance;
  document.getElementById('bet-input').value = '';
}

// ========== RENDERING ==========

function renderCard(card, clickHandler = null) {
  const cardEl = document.createElement('div');
  cardEl.className = 'card';
  if (clickHandler) cardEl.onclick = clickHandler;

  let imgPath = '';

  if (card.color === 'wild') {
    imgPath = card.value === '+4'
      ? 'assets/cards/plus_4.png'
      : 'assets/cards/wild.png';
  } else {
    let valueKey = card.value.toLowerCase()
      .replace('+', 'plus')
      .replace(/\s+/g, '');
    imgPath = `assets/cards/${card.color}_${valueKey}.png`;
  }

  cardEl.style.backgroundImage = `url('${imgPath}')`;
  cardEl.style.backgroundSize = 'cover';
  cardEl.style.backgroundPosition = 'center';
  cardEl.style.backgroundRepeat = 'no-repeat';
  cardEl.style.border = 'none';
  cardEl.style.width = '100px';
  cardEl.style.height = '150px';
  cardEl.style.borderRadius = '12px';
  cardEl.style.boxShadow = '0 6px 12px rgba(0,0,0,0.4)';

  return cardEl;
}

function updateDisplay() {
  const botEl = document.getElementById('bot-cards');
  const playerEl = document.getElementById('player-cards');
  const discardEl = document.getElementById('discard-pile');

  botEl.innerHTML = '';
  for (let i = 0; i < botHand.length; i++) {
    const cardBack = document.createElement('div');
    cardBack.className = 'card-back';
    cardBack.style.backgroundImage = "url('assets/card_back.png')";
    cardBack.style.backgroundSize = 'cover';
    cardBack.style.backgroundPosition = 'center';
    cardBack.style.border = 'none';
    botEl.appendChild(cardBack);
  }

  playerEl.innerHTML = '';
  playerHand.forEach((card, i) => {
    const el = renderCard(card, () => playCard(i));
    playerEl.appendChild(el);
  });

  if (discardPile.length > 0) {
    discardEl.innerHTML = '';
    discardEl.appendChild(renderCard(discardPile[discardPile.length - 1]));
  }

  document.getElementById('bot-card-count').textContent = botHand.length;
  document.getElementById('player-card-count').textContent = playerHand.length;
  document.getElementById('balance').textContent = balance;
}

function updateStatus(msg) {
  document.getElementById('status-bar').textContent = msg;
}

updateDisplay();
