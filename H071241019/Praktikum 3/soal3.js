const readline = require("readline");
const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
});

let hariArray = ["minggu", "senin", "selasa", "rabu", "kamis", "jumat", "sabtu"];

rl.question("Masukkan hari:", (hari) => {
    if (!hariArray.includes(hari.toLowerCase())) {
        console.log("Hari tidak valid!");
        return rl.close();
    }

    rl.question("Masukkan jumlah hari yang akan datang: ", (nInput) => {
        let n = parseInt(nInput);
        if (isNaN(n) || n < 0) {
            console.log("Jumlah hari tidak valid!");
            return rl.close();
        }

        let index = hariArray.indexOf(hari.toLowerCase());
        let hasilIndex = (index + n) % 7;
        console.log(`${n} hari setelah ${hari} adalah ${hariArray[hasilIndex]}`);
        rl.close();
    });
});