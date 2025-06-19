import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useT } from './i18n';

interface LoginProps {
  setToken: (token: string) => void;
}

export default function Login({ setToken }: LoginProps) {
  const t = useT();
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleSubmit = async (e: Event) => {
    e.preventDefault();
    setError('');
    try {
      const res = await fetch('/api/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password })
      });
      if (!res.ok) throw new Error(t('Invalid credentials'));
      const data = await res.json();
      localStorage.setItem('token', data.token);
      setToken(data.token);
      navigate('/dashboard');
    } catch {
      setError(t('Login failed'));
    }
  };

  return (
    <div className="container" style={{ maxWidth: '400px' }}>
      <h2 className="mb-3">{t('Login')}</h2>
      {error && <div className="alert alert-danger">{error}</div>}
      <form onSubmit={handleSubmit}>
        <div className="mb-3">
          <label className="form-label">{t('Username')}</label>
          <input className="form-control" value={username} onChange={e => setUsername((e.target as HTMLInputElement).value)} />
        </div>
        <div className="mb-3">
          <label className="form-label">{t('Password')}</label>
          <input type="password" className="form-control" value={password} onChange={e => setPassword((e.target as HTMLInputElement).value)} />
        </div>
        <button type="submit" className="btn btn-primary">{t('Login')}</button>
      </form>
    </div>
  );
}
