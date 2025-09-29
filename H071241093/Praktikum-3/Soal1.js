function countEventNumbers(start, end) {
  let jumlah = 0;
  let evenNumbers = [];

  for (let i = start; i <= end; i++) {
    if (i % 2 === 0) {
      evenNumbers.push(i);
      jumlah++;
    }
  }
  return `${jumlah} [${evenNumbers.join(", ")}]`;
}
console.log(countEventNumbers(1, 10));
