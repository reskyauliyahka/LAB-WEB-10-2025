const readline = require("readline");
const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
});

let target = Math.floor(Math.random() * 100) + 1;
let attempts = 0;

console.log("Tebak angka dari 1 sampai 100!");

function tanya() {
    rl.question("Masukkan tebakan: ", (input) => {
        let tebakan = parseInt(input);
        if (isNaN(tebakan) || tebakan < 1 || tebakan > 100) {
            console.log("Input harus angka antara 1-100!");
            return tanya();
        }

        attempts++;
        if (tebakan > target) {
            console.log("Terlalu tinggi! Coba lagi.");
            tanya();
        } else if (tebakan < target) {
            console.log("Terlalu rendah! Coba lagi.");
            tanya();
        } else {
            console.log(`Selamat! Kamu berhasil menebak angka ${target} dengan benar.`);
            console.log(`Sebanyak ${attempts}x percobaan.`);
            rl.close();
        }
    });
}

tanya();