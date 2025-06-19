import { useEffect, useState } from 'react';
import { Navigate, Link } from 'react-router-dom';
import { useT } from '../i18n';

interface Category { id: number; name: string; description?: string; }

export default function SongCategories({ token }: { token: string }) {
  const t = useT();
  const [categories, setCategories] = useState<Category[]>([]);
  const [form, setForm] = useState<Omit<Category, 'id'>>({ name: '', description: '' });
  const [editing, setEditing] = useState<number | null>(null);
  const [error, setError] = useState('');
  const headers = { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json' };

  const load = () => {
    fetch('/api/song-categories', { headers })
      .then(res => res.json())
      .then(setCategories);
  };
  useEffect(load, []);

  const submit = async () => {
    setError('');
    if (!form.name.trim()) { setError(t('Name is required')); return; }
    try {
      const res = await fetch(editing ? '/api/song-categories/' + editing : '/api/song-categories', {
        method: editing ? 'PUT' : 'POST',
        headers,
        body: JSON.stringify(form)
      });
      if (!res.ok) {
        const data = await res.json().catch(() => ({}));
        throw new Error(data.message || t('Request failed'));
      }
      setForm({ name: '', description: '' });
      setEditing(null);
      load();
    } catch (err: any) {
      setError(err.message);
    }
  };

  const edit = (c: Category) => { setForm({ name: c.name, description: c.description || '' }); setEditing(c.id); };
  const cancel = () => { setEditing(null); setForm({ name: '', description: '' }); setError(''); };
  const remove = async (id: number) => { await fetch('/api/song-categories/' + id, { method: 'DELETE', headers }); load(); };

  if (!token) return <Navigate to="/login" />;

  return (
    <div className="container" style={{ maxWidth: '600px' }}>
      <Link to="/dashboard" className="btn btn-link p-0 mb-2">&laquo; {t('Back')}</Link>
      <h2 className="mb-3">{t('Song Categories')}</h2>
      {error && <div className="alert alert-danger">{error}</div>}
      <div className="mb-3">
        <input className="form-control mb-1" placeholder={t('Name')} value={form.name}
          onChange={e => setForm({ ...form, name: (e.target as HTMLInputElement).value })} />
        <input className="form-control" placeholder={t('Description')} value={form.description}
          onChange={e => setForm({ ...form, description: (e.target as HTMLInputElement).value })} />
        <div className="mt-2">
          <button className="btn btn-primary" onClick={submit}>{editing ? t('Update') : t('Add')}</button>
          {editing && <button className="btn btn-secondary ms-2" onClick={cancel}>{t('Cancel')}</button>}
        </div>
      </div>
      <ul className="list-group">
        {categories.map(c => (
          <li key={c.id} className="list-group-item d-flex justify-content-between">
            <span>
              <strong>{c.name}</strong> {c.description}
            </span>
            <span>
              <button className="btn btn-sm btn-secondary me-2" onClick={() => edit(c)}>{t('Edit')}</button>
              <button className="btn btn-sm btn-danger" onClick={() => remove(c.id)}>{t('Delete')}</button>
            </span>
          </li>
        ))}
      </ul>
    </div>
  );
}
