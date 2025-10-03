const readline = require('readline')

const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
})

const target = Math.floor(Math.random() * 100) + 1
let attempts = 0

console.log("Tebak angka antara 1 sampai 100!")

const askGuess = () => {
    rl.question("masukkan salah satu dari angka 1 sampai 100: ", (input) => {
        const guess = parseInt(input)

        if (isNaN(guess) || guess < 1 || guess > 100){
            console.log("input tidak valid. Masukkan angka antara 1 dan 100")
            askGuess()
            return
        }

        attempts++

        if (guess > target){
            console.log("terlalu tinggi! coba lagi")
            askGuess()
        } else if (guess < target){
            console.log("terlalu rendah! coba lagi")
            askGuess()
        } else{
            console.log("\nSelamat! Kamu berhasil menebak angka" + target + "dengan benar")
            console.log("Sebanyak " + attempts + "x percobaan")
            rl.close()
            }
    })
}

askGuess()