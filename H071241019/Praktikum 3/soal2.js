const readline = require("readline");
const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
});

rl.question("Masukkan harga barang: ", (hargaInput) => {
    let harga = parseFloat(hargaInput);
    if (isNaN(harga) || harga <= 0) {
        console.log("Input harga tidak valid!");
        return rl.close();
    }

    rl.question("Masukkan jenis barang (Elektronik, Pakaian, Makanan, Lainnya): ", (jenisInput) => {
        let jenis = jenisInput.toLowerCase();
        let diskon = 0;

        switch (jenis) {
            case "elektronik": diskon = 0.1; break;
            case "pakaian": diskon = 0.2; break;
            case "makanan": diskon = 0.05; break;
            default: diskon = 0;
        }

        let hargaAkhir = harga - (harga * diskon);
        console.log(`Harga awal: Rp ${harga}`);
        console.log(`Diskon: ${diskon * 100}%`);
        console.log(`Harga setelah diskon: Rp ${hargaAkhir}`);
        rl.close();
    });
});