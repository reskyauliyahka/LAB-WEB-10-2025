const readline = require("readline");

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout,
});

function countEvenNumber(awal, akhir) {
  let angka = 0;
  let arrayAngkagenap = [];

  for (let i = awal; i <= akhir; i++) {
    if (i % 2 === 0) {
      angka++;
      arrayAngkagenap.push(i);
    }
  }

  return `Output : ${angka} [${arrayAngkagenap}]`;
}

rl.question("Masukkan angka awal :", (awal) => {
  rl.question("Masukkan angka akhir :", (akhir) => {
    const angkaAwal = parseInt(awal);
    const angkaAkhir = parseInt(akhir);

    if (isNaN(angkaAwal) || isNaN(angkaAkhir)) {
      console.log("Error: Input harus berupa angka integer.");
      rl.close();
      return;
    }

    if (angkaAwal > angkaAkhir) {
      console.log("Error: Angka awal tidak boleh lebih besar dari angka akhir.");
      rl.close();
      return;
    }

    console.log(countEvenNumber(angkaAwal, angkaAkhir));
    rl.close();
  });
});
