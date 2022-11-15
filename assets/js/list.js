const ROOT_URL = 'http://localhost:5000/api';

const fetchMinerals = async () => {
  let response = await fetch(`${ROOT_URL}/all_minerals`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json;charset=utf-8',
    },
  });
  let result = await response.json();
  const minerals = result;

  const items = [];
  for (let mineral of minerals) {
    const newItem = document.createElement('div');
    newItem.classList.add('item');
    const itemImg = document.createElement('img');
    itemImg.src = mineral?.photo;
    itemImg.alt = 'item img';
    const pItem = document.createElement('p');
    pItem.textContent = mineral.name;
    newItem.appendChild(itemImg);
    newItem.appendChild(pItem);
    const btn = document.createElement('button');
    btn.onclick = () => {
      window.location.href =
        'http://127.0.0.1:5500/pages/product.html?id=' + mineral.id;
    };
    btn.textContent = 'Подробнее';
    newItem.appendChild(btn);
    items.push(newItem);
  }

  fillPage(items);
};

const fillPage = (items) => {
  const list = document.getElementById('item-list');

  for (let item of items) {
    list.appendChild(item);
  }
};

fetchMinerals();

// const onLoadHanlder = () => {};
