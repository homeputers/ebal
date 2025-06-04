import { useEffect } from 'react';

export default function useTheme(): void {
  useEffect(() => {
    const match = window.matchMedia('(prefers-color-scheme: dark)');
    const apply = () =>
      document.documentElement.setAttribute('data-bs-theme', match.matches ? 'dark' : 'light');
    apply();
    match.addEventListener('change', apply);
    return () => match.removeEventListener('change', apply);
  }, []);
}
