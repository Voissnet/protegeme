document.addEventListener('DOMContentLoaded', (event) => {
   const parser = new UAParser();
   screen.orientation.addEventListener('change', function (e) {
      if (parser.getResult().device.type === 'mobile' && screen.orientation.type === 'landscape-primary') {
         document.body.style.height = 'auto'
      } else {
         document.body.style.height = '100%'
      }
   });
   if (parser.getResult().device.type === 'mobile' && screen.orientation.type === 'landscape-primary') {
      document.body.style.height = 'auto'
   } else {
      document.body.style.height = '100%'
   }
});

const mainNav = document.getElementById('main-nav');
const mainMenu = document.getElementById('main-menu');
window.addEventListener('resize', () => {
   if (mainNav.classList.contains('nav--show')) {
      mainNav.classList.remove('nav--show');
      mainMenu.classList.remove('main-menu--show');
   }
});