function findIndices(targetSum, numbers) {
    let indice1 = 0;
    let indice2 = numbers.length - 1;

    while (indice1 < indice2) {
        const currentSum = numbers[indice1] + numbers[indice2];

        if (currentSum === targetSum) {
            return `The number ${numbers[indice1]} and ${numbers[indice2]} make the sum ${targetSum}. The indices of the number ${numbers[indice1]} is (${indice1}) and ${numbers[indice2]} is (${indice2})`;
        } else if (currentSum < targetSum) {
            indice1++;
        } else {
            indice2--;
        }
    }

    return "No indices are found that match the sum!";
}

// Example usage
const targetSum = 9;
const numbers = [1, 2, 3, 4, 5, 6];

const result = findIndices(targetSum, numbers);
console.log(result);