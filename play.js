/**
let foods = new Map();
foods.set('italian', 'gelato');
foods.set('mexican', 'tortas');
foods.set('canadian', 'poutine');

let southernUsStates = ['Tennessee', 'Kentucky', 'Texas'];
foods.set(southernUsStates, 'hot_chicken');
console.log(
	foods.get('italian'),
	foods.has('french'),
	foods.get(southernUsStates),
	foods.size
);


let foods = new WeakMap();
foods.set(['italian'], 'gelato');
foods.set(['mexican'], 'tortas');
foods.set(['canadian'], 'poutine');

let southernUsStates = ['Tennessee', 'Kentucky', 'Texas'];
foods.set(southernUsStates, 'hot_chicken');
southernUsStates = null

console.log(
	foods.get(['italian']), //undefined
	foods.get(southernUsStates), //undefined
	foods.size //undefined
);

*/

let foods = new Set();
foods.add('gelato');
foods.add('tortas');
foods.add('gelato');

console.log(foods); //Set { 'gelato', 'tortas' }