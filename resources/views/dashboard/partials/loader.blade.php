<style>
/* --- Loader Container --- */
#loader-root {
  background: linear-gradient(135deg, #5b21b6, #7c3aed); /* بنفسجي متدرج */
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: fixed;
  inset: 0;
  z-index: 9999;
  overflow: hidden;
}

/* --- Loader Text Wrapper --- */
.loader {
  position: relative;
  display: inline-block;
  user-select: none;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

/* --- Outline Text (خفيف للعمق) --- */
.loader .outline {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: transparent;
  -webkit-text-stroke: 2px rgba(255,255,255,0.5); /* أبيض نصف شفاف */
  font-size: 120px;
  letter-spacing: 6px;
  font-weight: 800;
}

/* --- Fill Text مع تأثير نبض --- */
.loader .fill {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: #ffffff; /* أبيض */
  -webkit-text-stroke: 0px;
  font-size: 120px;
  letter-spacing: 6px;
  font-weight: 800;
  animation: pulseText 1.8s ease-in-out infinite, waveClip 2s ease-in-out infinite;
}

/* --- نبض النص --- */
@keyframes pulseText {
  0%, 100% {
    transform: translate(-50%, -50%) scale(1);
    opacity: 0.95;
  }
  50% {
    transform: translate(-50%, -50%) scale(1.05);
    opacity: 1;
  }
}

/* --- حركة wave للنص --- */
@keyframes waveClip {
  0%, 100% {
    clip-path: polygon(
      0% 45%, 15% 44%, 32% 50%,
      54% 60%, 70% 61%, 84% 59%,
      100% 52%, 100% 100%, 0% 100%
    );
  }
  50% {
    clip-path: polygon(
      0% 60%, 16% 65%, 34% 66%,
      51% 62%, 67% 50%, 84% 45%,
      100% 46%, 100% 100%, 0% 100%
    );
  }
}

/* --- Responsive small screens --- */
@media (max-width: 480px) {
  .loader .outline,
  .loader .fill {
    font-size: 64px;
    letter-spacing: 4px;
  }
}
</style>

<div id="loader-root" class="loader-root">
    <div class="loader" role="img" aria-label="Estate loading">
        <span class="outline">Estate</span>
        <span class="fill">Estate</span>
    </div>
</div>
