const readline = require('readline')

const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
})

const hitungDiskon = (harga, jenis) => {
    let diskon = 0

    switch (jenis.toLowerCase()){
        case 'elektronik':
            diskon = 0.10
            break
        case 'pakaian':
            diskon = 0.20
            break
        case 'makanan':
            diskon = 0.05
            break
        case 'lainnya':
            diskon = 0.00
            break
        default:
            return null
    }
    const hargaDiskon = harga - (harga * diskon)
    return{
        hargaAwal: harga,
        diskonPersen: diskon * 100,
        hargaSetelahDiskon: hargaDiskon
    }
}

rl.question('Masukkan harga barang: ', (hargaInput) => {
    const harga = parseFloat(hargaInput)

    if (isNaN(harga)){
        console.log("Harap masukkan angka")
        rl.close()
        return
    } else if (harga <= 0){
        console.log("harap masukkan angka positif")
        rl.close()
        return
    }

    rl.question('masukkan jenis barang (elektronik, pakaian, makanan lainnya): ', (jenisInput) => {
        const hasil = hitungDiskon(harga, jenisInput)

        if (hasil === null){
            console.log("Jenis barang tidak dikenali")
        } else {
            console.log("\nHarga awal: " + hasil.hargaAwal)
            console.log("\nDiskon: " + hasil.diskonPersen + '%')
            console.log("\nHarga setelah diskon: " + hasil.hargaSetelahDiskon)
        }

        rl.close()
    })

})


