import { Outlet } from 'react-router-dom';
import LanguageToggle from './LanguageToggle';
import { useT } from './i18n';

interface LayoutProps {
  onLogout: () => void;
}

export default function Layout({ onLogout }: LayoutProps) {
  const t = useT();
  return (
    <div className="container py-3">
      <header className="d-flex justify-content-between align-items-center mb-3">
        <LanguageToggle />
        <button
          onClick={onLogout}
          className="btn btn-sm btn-outline-danger"
        >
          {t('Logout')}
        </button>
      </header>
      <main>
        <Outlet /> {/* Child routes will render here */}
      </main>
    </div>
  );
}