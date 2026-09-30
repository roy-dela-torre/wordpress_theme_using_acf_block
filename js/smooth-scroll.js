// Smooth scrolling functionality using JavaScript
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
	anchor.addEventListener('click', function (e) {
		e.preventDefault();
		const targetElement = 
				document.querySelector(this.getAttribute('href'));
		window.scrollTo({
			top: targetElement.offsetTop + - 180,
			behavior: 'smooth'
		});
	});
});