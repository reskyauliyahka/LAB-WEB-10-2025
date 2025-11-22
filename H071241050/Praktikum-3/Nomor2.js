const readline = require("readline"); 

const rl = readline.createInterface({ //antarmuka 
  input: process.stdin,
  output: process.stdout
});

rl.question("Masukkan harga barang: ", (hargaInput) => {
  const harga = parseFloat(hargaInput);

  if (isNaN(harga) || harga <= 0) {
    console.log("Input harga tidak valid!");
    rl.close(); //tutup input agar program slsai
    return; 
  }

  rl.question("Masukkan jenis barang (Elektronik, Pakaian, Makanan, Lainnya): ", (jenis) => {
    let diskon = 0; 

    switch (jenis.toLowerCase()) { 
      case "elektronik":
        diskon = 0.1;
        break;
      case "pakaian":
        diskon = 0.2;
        break;
      case "makanan":
        diskon = 0.05;
        break;
      case "lainnya":
        diskon = 0;
        break;
      default:
        console.log("Jenis barang tidak valid!");
        rl.close();
        return;
    }

    const potongan = harga * diskon;
    const hargaAkhir = harga - potongan;

    console.log(`Harga awal: Rp ${harga}`);
    console.log(`Diskon: ${diskon * 100}%`);
    console.log(`Harga setelah diskon: Rp ${hargaAkhir}`);

    rl.close();
  });
});
