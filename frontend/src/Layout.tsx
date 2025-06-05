import { Outlet } from 'react-router-dom';

interface LayoutProps {
  onLogout: () => void;
}

export default function Layout({ onLogout }: LayoutProps) {
  return (
    <div className="container py-3">
      <header className="d-flex justify-content-end mb-3">
        <button
          onClick={onLogout}
          className="btn btn-sm btn-outline-danger"
        >
          Logout
        </button>
      </header>
      <main>
        <Outlet /> {/* Child routes will render here */}
      </main>
    </div>
  );
}