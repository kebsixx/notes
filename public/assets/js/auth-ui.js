(function () {
  var toggles = document.querySelectorAll('[data-toggle-password]');

  toggles.forEach(function (button) {
    button.addEventListener('click', function () {
      var targetId = button.getAttribute('data-target');
      if (!targetId) return;

      var input = document.getElementById(targetId);
      if (!input) return;

      var show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      button.setAttribute('aria-pressed', show ? 'true' : 'false');
      button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');

      var eye = button.querySelector('.auth-icon-svg-eye');
      var eyeOff = button.querySelector('.auth-icon-svg-eye-off');
      if (eye && eyeOff) {
        eye.hidden = show;
        eyeOff.hidden = !show;
      }
    });
  });
})();
