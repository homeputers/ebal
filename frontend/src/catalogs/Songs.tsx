import { useEffect, useState } from 'react';
import { Navigate, Link } from 'react-router-dom';
import { useT } from '../i18n';

interface Song { id: number; title: string; category_id?: number; original_key?: string; original_author?: string; }
interface Category { id: number; name: string; }

export default function Songs({ token }: { token: string }) {
  const t = useT();
  const [songs, setSongs] = useState<Song[]>([]);
  const [categories, setCategories] = useState<Category[]>([]);
  const [form, setForm] = useState<Omit<Song, 'id'>>({ title: '', category_id: undefined, original_key: '', original_author: '' });
  const [editing, setEditing] = useState<number | null>(null);
  const [error, setError] = useState('');
  const headers = { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json' };

  const load = () => {
    fetch('/api/songs', { headers }).then(res => res.json()).then(setSongs);
    fetch('/api/song-categories', { headers }).then(res => res.json()).then(setCategories);
  };
  useEffect(load, []);

  const submit = async () => {
    setError('');
    if (!form.title.trim()) { setError(t('Title is required')); return; }
    try {
      const res = await fetch(editing ? '/api/songs/' + editing : '/api/songs', {
        method: editing ? 'PUT' : 'POST',
        headers,
        body: JSON.stringify(form)
      });
      if (!res.ok) {
        const data = await res.json().catch(() => ({}));
        throw new Error(data.message || t('Request failed'));
      }
      setForm({ title: '', category_id: undefined, original_key: '', original_author: '' });
      setEditing(null);
      load();
    } catch (err: any) {
      setError(err.message);
    }
  };

  const edit = (s: Song) => { setForm({ title: s.title, category_id: s.category_id, original_key: s.original_key || '', original_author: s.original_author || '' }); setEditing(s.id); };
  const cancel = () => { setEditing(null); setForm({ title: '', category_id: undefined, original_key: '', original_author: '' }); setError(''); };
  const remove = async (id: number) => { await fetch('/api/songs/' + id, { method: 'DELETE', headers }); load(); };

  if (!token) return <Navigate to="/login" />;

  return (
    <div className="container" style={{ maxWidth: '600px' }}>
      <Link to="/dashboard" className="btn btn-link p-0 mb-2">&laquo; {t('Back')}</Link>
      <h2 className="mb-3">{t('Songs')}</h2>
      {error && <div className="alert alert-danger">{error}</div>}
      <div className="mb-3">
        <input className="form-control mb-1" placeholder={t('Title')} value={form.title}
          onChange={e => setForm({ ...form, title: (e.target as HTMLInputElement).value })} />
        <select className="form-select mb-1" value={form.category_id || ''}
          onChange={e => setForm({ ...form, category_id: +(e.target as HTMLSelectElement).value })}>
          <option value="">{t('No category')}</option>
          {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
        </select>
        <input className="form-control mb-1" placeholder={t('Original Key')} value={form.original_key}
          onChange={e => setForm({ ...form, original_key: (e.target as HTMLInputElement).value })} />
        <input className="form-control" placeholder={t('Original Author')} value={form.original_author}
          onChange={e => setForm({ ...form, original_author: (e.target as HTMLInputElement).value })} />
        <div className="mt-2">
          <button className="btn btn-primary" onClick={submit}>{editing ? t('Update') : t('Add')}</button>
          {editing && <button className="btn btn-secondary ms-2" onClick={cancel}>{t('Cancel')}</button>}
        </div>
      </div>
      <ul className="list-group">
        {songs.map(s => (
          <li key={s.id} className="list-group-item d-flex justify-content-between">
            <span>
              <strong>{s.title}</strong> {s.original_key} {s.original_author}
            </span>
            <span>
              <button className="btn btn-sm btn-secondary me-2" onClick={() => edit(s)}>{t('Edit')}</button>
              <button className="btn btn-sm btn-danger" onClick={() => remove(s.id)}>{t('Delete')}</button>
            </span>
          </li>
        ))}
      </ul>
    </div>
  );
}
