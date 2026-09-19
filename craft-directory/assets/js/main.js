document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('.menu-toggle');
  const nav = document.getElementById('mainNav');
  if (toggle) toggle.addEventListener('click', () => nav.classList.toggle('open'));

  const craftSearch = document.getElementById('craftSearch');
  const categoryFilter = document.getElementById('categoryFilter');
  const craftGrid = document.getElementById('craftGrid');
  const craftCount = document.getElementById('craftCount');

  if (craftGrid) {
    const cards = Array.from(craftGrid.querySelectorAll('.craft-card'));
    const applyFilter = () => {
      const q = (craftSearch?.value || '').toLowerCase().trim();
      const cat = categoryFilter?.value || '';
      let visible = 0;
      cards.forEach(card => {
        const name = card.dataset.name || '';
        const category = card.dataset.category || '';
        const show = (!q || name.includes(q)) && (!cat || category === cat);
        card.style.display = show ? '' : 'none';
        if (show) visible++;
      });
      if (craftCount) craftCount.textContent = `✦ ${visible} craft(s) found`;
    };
    craftSearch?.addEventListener('input', applyFilter);
    categoryFilter?.addEventListener('change', applyFilter);
    applyFilter();
  }

  const header = document.querySelector('.site-header');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 30) header.classList.add('scrolled');
    else header.classList.remove('scrolled');
  });
});