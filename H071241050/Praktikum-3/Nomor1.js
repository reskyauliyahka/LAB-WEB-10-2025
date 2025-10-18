function countEvenNumbers(start, end) {
  let result = [];
  for (let i = start; i <= end; i++) {
    if (i % 2 === 0) {
      result.push(i); 
    }
  }
  return `${result.length} [${result.join(", ")}]`; 
}

console.log(countEvenNumbers(1, 10)); 
