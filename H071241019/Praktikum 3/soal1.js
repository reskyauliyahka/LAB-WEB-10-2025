function hitungAngkaGenap(start, end) {
    if (isNaN(start) || isNaN(end)) {
        console.log("Inputan harus berupa angka!");
        return;
    }

    let genap = [];
    for (let i = start; i <= end; i++) {
        if (i % 2 === 0) {
            genap.push(i);
        }
    }
    return { count: genap.length, numbers: genap };
}

let hasil = hitungAngkaGenap(1, 10);
if (hasil) {
    console.log(`${hasil.count} [${hasil.numbers.join(", ")}]`);
}