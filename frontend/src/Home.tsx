import { Link } from 'react-router-dom';
import { useT } from './i18n';

export default function Home() {
  const t = useT();
  return (
    <div className="container text-center">
      <h1 className="mb-3">{t('Welcome to Ebal')}</h1>
      <p className="mb-4">{t('Every Breath and Life portal')}</p>
      <Link className="btn btn-primary" to="/login">{t('Login')}</Link>
    </div>
  );
}
