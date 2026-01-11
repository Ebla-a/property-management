<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RealEstateSys - Login</title>

<link rel="icon" href="{{ asset('logo.png') }}">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
html,body{height:100%;font-family:'Poppins',sans-serif}
.no-scroll{overflow:hidden;height:100vh}

.cloned-image{
  position:fixed;
  z-index:1000;
  object-fit:cover;
  pointer-events:none;
  transition:transform .6s cubic-bezier(.2,.9,.3,1), opacity .35s ease;
}

#loader{
  position:fixed;
  inset:0;
  display:none;
  align-items:center;
  justify-content:center;
  background:linear-gradient(135deg,#5b21b6,#7c3aed);
  z-index:1100;
  color:white;
}
#loader.show{display:flex}

.spinner{
  width:64px;height:64px;
  border:6px solid rgba(255,255,255,.3);
  border-top-color:white;
  border-radius:50%;
  animation:spin 1s linear infinite;
}
@keyframes spin{to{transform:rotate(360deg)}}
</style>
</head>

<body class="bg-white">

<div class="min-h-screen grid grid-cols-1 md:grid-cols-2">

<!-- FORM -->
<div class="flex items-center justify-center px-6 md:order-2">
  <div class="w-full max-w-md">

    <div class="flex flex-col items-center justify-center gap-3 mb-8">
      <img src="{{ asset('logo.png') }}" class="h-12 w-12 rounded">
      <span class="text-indigo-600 text-4xl font-bold">Real-Estate System</span>
    </div>

<form id="loginForm" method="POST" action="{{ route('login') }}">
  @csrf

  <div class="mb-4">
    <label>Email</label>
    <input name="email" required class="mt-2 w-full border rounded px-4 py-3">
  </div>

  <div class="mb-4">
    <label>Password</label>
    <input type="password" name="password" required class="mt-2 w-full border rounded px-4 py-3">
  </div>
  <!-- Forgot password link -->
    <a href="{{ route('password.request') }}" class="text-indigo-600 hover:underline text-sm">
      Forgot your password?
    </a>
  <button id="loginBtn"
    class="w-full mt-6 bg-indigo-600 hover:bg-indigo-500 text-white py-3 rounded">
    Login
  </button>
</form>


  </div>
</div>

<!-- IMAGE -->
<div id="imageCol" class="relative hidden md:block overflow-hidden">
  <img id="sideImg" src="{{ asset('Estate.webp') }}" class="absolute inset-0 w-full h-full object-cover">
  <div class="absolute inset-0 flex items-center justify-center text-white text-5xl font-bold">
    Login To Manage Your Properties
  </div>

  <div class="absolute inset-0 bg-indigo-900/50"></div>
</div>


<!-- LOADER -->
<div id="loader">
  <div class="flex flex-col items-center gap-4">
    <div class="spinner"></div>
    <div class="font-semibold">Signing you in…</div>
  </div>
</div>

<script>
(function(){
  const form   = document.getElementById('loginForm');
  const btn    = document.getElementById('loginBtn');
  const img    = document.getElementById('sideImg');
  const imgCol = document.getElementById('imageCol');
  const loader = document.getElementById('loader');

  let submitted = false;

  form.addEventListener('submit', function(e){
    if (submitted) return;
    e.preventDefault();
    submitted = true;

    btn.disabled = true;
    document.body.classList.add('no-scroll');

    document.activeElement?.blur();

    if (!img || window.innerWidth < 768) {
      loader.classList.add('show');
      form.submit();
      return;
    }

    const r = img.getBoundingClientRect();
    const clone = img.cloneNode(true);
    clone.className = 'cloned-image';

    Object.assign(clone.style,{
      left: r.left + 'px',
      top: r.top + 'px',
      width: r.width + 'px',
      height: r.height + 'px'
    });

    imgCol.style.visibility = 'hidden';
    document.body.appendChild(clone);

    const tx = window.innerWidth / 2 - (r.left + r.width / 2);
    const ty = window.innerHeight / 2 - (r.top + r.height / 2);
    const scale = Math.max(
      window.innerWidth / r.width,
      window.innerHeight / r.height
    ) * 1.05;

    requestAnimationFrame(() => {
      clone.style.transform =
        `translate3d(${tx}px, ${ty}px, 0) scale(${scale})`;
    });

    clone.addEventListener('transitionend', () => {
      loader.classList.add('show');
      clone.style.opacity = '0';

      document.activeElement?.blur(); // تأكيد
      setTimeout(() => form.submit(), 100);
    }, { once: true });
  });
})();
</script>

</body>
</html>
