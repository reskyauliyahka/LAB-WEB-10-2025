const readline = require("readline");

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});

const randomNumber = Math.floor(Math.random() * 100) + 1;
let percobaan = 0;

function tebakAngka() {
  rl.question("Masukkan salah satu dari angka 1 sampai 100: ", (input) => {
    const tebakan = parseInt(input); // ubah jd blngn bulat
    percobaan++;

    if (isNaN(tebakan) || tebakan < 1 || tebakan > 100) {
      console.log("Input tidak valid! Harap masukkan angka 1 - 100.");
      tebakAngka();
      return;
    }

    if (tebakan > randomNumber) {
      console.log("Terlalu tinggi! Coba lagi.");
      tebakAngka();
    } else if (tebakan < randomNumber) {
      console.log("Terlalu rendah! Coba lagi.");
      tebakAngka();
    } else {
      console.log(`Selamat! kamu berhasil menebak angka ${randomNumber} dengan benar.`);
      console.log(`Sebanyak ${percobaan}x percobaan.`);
      rl.close();
    }
  });
}

tebakAngka();
