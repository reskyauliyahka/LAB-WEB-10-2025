
const readline = require('readline');

const angkaRahasia = Math.floor(Math.random() * 100) + 1;
let jumlahTebakan = 0;

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});

console.log("Halo! Aku telah memilih sebuah angka rahasia antara 1 dan 100. Coba tebak!");


function mulaiTebak() {
  rl.question('Masukkan tebakan Anda (angka 1-100): ', (input) => {
    const tebakan = parseInt(input);
    jumlahTebakan++;
    if (isNaN(tebakan)) {
      console.log('Input tidak valid! Harap masukkan angka.');
      mulaiTebak(); 
      return;
    }

    if (tebakan > angkaRahasia) {
      console.log('Terlalu tinggi! Coba lagi.'); 
      mulaiTebak(); 
    } else if (tebakan < angkaRahasia) {
      console.log('Terlalu rendah! Coba lagi.'); 
      mulaiTebak(); 
    } else {
      console.log(`\nSelamat! Kamu berhasil menebak angka ${angkaRahasia} dengan benar.`); 
      console.log(`Kamu berhasil dalam ${jumlahTebakan}x percobaan.`); 
      rl.close();
    }
  });
}

mulaiTebak();