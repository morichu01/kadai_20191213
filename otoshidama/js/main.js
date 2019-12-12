'use strict';
{
  const btn = document.getElementById('btn');
  btn.addEventListener('click', () => {
    const results = ['歳 x 1000円', '歳 x 500円', '学年 x 500円', '学年 x 1000円', '無し'];

    // 確率でおみくじを設定する方法
    const n = Math.random();
    console.log(n);
    if (n < 0.05) {
      btn.textContent = results[0]; // 5%
    } else if (n < 0.15) {
      btn.textContent = results[3]; // 10%
    } else if (n < 0.55) {
      btn.textContent = results[2]; // 40%
    } else if (n < 0.95) {
      btn.textContent = results[1]; // 40%
    } else {
      btn.textContent = results[4]; // 5%
    }
  });
}