function initUsMap() {
	var blocks = document.querySelectorAll('.lp-us-map');

	blocks.forEach(function (block) {
		var wrapper = block.querySelector('.lp-us-map__map-wrapper');
		if (!wrapper) return;

		var stateData = {};

		try {
			stateData = JSON.parse(wrapper.dataset.states || '{}');
		} catch (e) {
			console.log(e);
		}

		var paths = wrapper.querySelectorAll('svg path[id]');

		paths.forEach(function (path) {
			var code = path.id.toUpperCase();
			var baseCode = code.replace(/-.*$/, '');
			var state = stateData[baseCode];

			if (state && state.url) {
				path.classList.add('lp-us-map__state--linked');

				path.addEventListener('click', function () {
					window.open(state.url, state.target || '_self');
				});
			}
		});

		var legendLinks = block.querySelectorAll('.lp-us-map__legend a[data-state]');

		legendLinks.forEach(function (link) {
			var stateCode = link.dataset.state.toUpperCase();

			link.addEventListener('mouseenter', function () {
				paths.forEach(function (path) {
					var baseCode = path.id.toUpperCase().replace(/-.*$/, '');
					if (baseCode === stateCode) {
						path.classList.add('lp-us-map__state--hover');
					}
				});
			});

			link.addEventListener('mouseleave', function () {
				paths.forEach(function (path) {
					path.classList.remove('lp-us-map__state--hover');
				});
			});
		});
	});
}

document.addEventListener('DOMContentLoaded', initUsMap);
document.addEventListener('usMapBlockLoaded', initUsMap);
