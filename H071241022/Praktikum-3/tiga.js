const readline = require("readline")

const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
})

const days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu']

rl.question("Masukkan hari: ", (hariInput) => {
    const hari = hariInput.toLowerCase()
    const indexHari = days.indexOf(hari)

    if (indexHari === -1){
        console.log("hari tidak valid")
        rl.close()
        return
    }

    rl.question("Masukkan jumlah hari yang akan datang ", (jumlahInput) => {
        const jumlahHari = parseInt(jumlahInput)

        if (isNaN(jumlahHari)){
            console.log("masukkan jumlah hari")
            rl.close()
            return
        }else if (jumlahHari < 0){
            console.log("masukakn jumlah hari yang valid (angka positif")
            rl.close()
            return
        }

        const indexHariMasaDepan = (indexHari + jumlahHari) % 7
        const hariMasaDepan = days[indexHariMasaDepan]

        console.log(jumlahHari + " hari setelah " + hari + " adalah " + hariMasaDepan)
        rl.close()
    })
})