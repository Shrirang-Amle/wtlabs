function createInputs(){
    let size = document.getElementById("size").value;
    let container = document.getElementById("inputs");

    container.innerHTML="";

    for(let i=0;i<size;i++){
        container.innerHTML += 'Enter Element : '+i+' <input type="number"><br>';
    }
}

function sortArray(){

   let arr = [];
   let inputs = document.querySelectorAll("#inputs input");

   inputs.forEach(input => {
       arr.push(parseInt(input.value));
   });

   document.getElementById('printarr').innerHTML = arr;

   let sortType = document.querySelector('input[name="sortType"]:checked').value;

   if(sortType === "bubble"){

        let bubble = [...arr];

        for (let i = 0; i < bubble.length; i++) {
            for (let j = 0; j < bubble.length - i - 1; j++) {
                if (bubble[j] > bubble[j + 1]) {
                    let temp = bubble[j];
                    bubble[j] = bubble[j + 1];
                    bubble[j + 1] = temp;
                }
            }
        }

        document.getElementById('printsort').innerHTML = bubble;
        document.getElementById('printmerge').innerHTML = "";

   }

   else{

        let mergeSorted = mergeSort([...arr]);
        document.getElementById('printmerge').innerHTML = mergeSorted;
        document.getElementById('printsort').innerHTML = "";

   }
}


function reverseArray(){

   let arr = [];
   let inputs = document.querySelectorAll("#inputs input");

   inputs.forEach(input => {
       arr.push(parseInt(input.value));
   });

   let start = 0;
   let end = arr.length - 1;

   while(start < end){
        let temp = arr[start];
        arr[start] = arr[end];
        arr[end] = temp;
        start++;
        end--;
   }

   document.getElementById('printrev').innerHTML = arr;
}

function searchElement(){

   let arr = [];
   let inputs = document.querySelectorAll("#inputs input");

   inputs.forEach(input => {
       arr.push(parseInt(input.value));
   });

   let target = parseInt(document.getElementById("target").value);

   for(let i=0;i<arr.length;i++){

        if(arr[i] == target){
            document.getElementById("searchresult").innerHTML="Element Found at index "+i;
            return;
        }
   }

   document.getElementById("searchresult").innerHTML="Element Not Found";
}

function mergeSort(arr) {

    if (arr.length <= 1) {
        return arr;
    }

    let mid = Math.floor(arr.length / 2);

    let left = mergeSort(arr.slice(0, mid));
    let right = mergeSort(arr.slice(mid));

    return merge(left, right);
}


function merge(left, right) {

    let result = [];
    let i = 0;
    let j = 0;

    while (i < left.length && j < right.length) {

        if (left[i] <= right[j]) {
            result.push(left[i]);
            i++;
        } 
        else {
            result.push(right[j]);
            j++;
        }

    }

    while (i < left.length) {
        result.push(left[i]);
        i++;
    }

    while (j < right.length) {
        result.push(right[j]);
        j++;
    }

    return result;
}