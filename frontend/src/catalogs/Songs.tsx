import { useEffect, useState } from 'react';
import { Navigate } from 'react-router-dom';

interface Song { id: number; title: string; category_id?: number; original_key?: string; original_author?: string; }
interface Category { id: number; name: string; }

export default function Songs({ token }: { token: string }) {
  const [songs, setSongs] = useState<Song[]>([]);
  const [categories, setCategories] = useState<Category[]>([]);
  const [form, setForm] = useState<Omit<Song, 'id'>>({ title: '', category_id: undefined, original_key: '', original_author: '' });
  const [edit, setEdit] = useState<Song | null>(null);
  const headers = { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json' };

  const load = () => {
    fetch('/api/songs', { headers }).then(res => res.json()).then(setSongs);
    fetch('/api/song-categories', { headers }).then(res => res.json()).then(setCategories);
  };
  useEffect(load, []);

  const create = () => {
    fetch('/api/songs', { method: 'POST', headers, body: JSON.stringify(form) })
      .then(() => { setForm({ title: '', category_id: undefined, original_key: '', original_author: '' }); load(); });
  };

  const update = () => {
    if (!edit) return;
    fetch('/api/songs/' + edit.id, { method: 'PUT', headers, body: JSON.stringify(edit) })
      .then(() => { setEdit(null); load(); });
  };

  const remove = (id: number) => {
    fetch('/api/songs/' + id, { method: 'DELETE', headers }).then(load);
  };

  if (!token) return <Navigate to="/login" />;

  return (
    <div className="container">
      <h2 className="mb-3">Songs</h2>
      <ul className="list-group mb-3">
        {songs.map(s => (
          <li key={s.id} className="list-group-item">
            {edit && edit.id === s.id ? (
              <div>
                <input className="form-control mb-1" value={edit.title}
                  onChange={e => setEdit({ ...edit, title: (e.target as HTMLInputElement).value })} />
                <select className="form-select mb-1" value={edit.category_id || ''}
                  onChange={e => setEdit({ ...edit, category_id: +(e.target as HTMLSelectElement).value })}>
                  <option value="">No category</option>
                  {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                </select>
                <input className="form-control mb-1" value={edit.original_key || ''}
                  onChange={e => setEdit({ ...edit, original_key: (e.target as HTMLInputElement).value })} />
                <input className="form-control" value={edit.original_author || ''}
                  onChange={e => setEdit({ ...edit, original_author: (e.target as HTMLInputElement).value })} />
                <div className="mt-2">
                  <button className="btn btn-sm btn-primary me-2" onClick={update}>Save</button>
                  <button className="btn btn-sm btn-secondary" onClick={() => setEdit(null)}>Cancel</button>
                </div>
              </div>
            ) : (
              <div className="d-flex justify-content-between">
                <span>
                  <strong>{s.title}</strong> {s.original_key} {s.original_author}
                </span>
                <span>
                  <button className="btn btn-sm btn-secondary me-2" onClick={() => setEdit(s)}>Edit</button>
                  <button className="btn btn-sm btn-danger" onClick={() => remove(s.id)}>Delete</button>
                </span>
              </div>
            )}
          </li>
        ))}
      </ul>
      <h4>Add Song</h4>
      <div className="mb-2">
        <input className="form-control mb-1" placeholder="Title" value={form.title}
          onChange={e => setForm({ ...form, title: (e.target as HTMLInputElement).value })} />
        <select className="form-select mb-1" value={form.category_id || ''}
          onChange={e => setForm({ ...form, category_id: +(e.target as HTMLSelectElement).value })}>
          <option value="">No category</option>
          {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
        </select>
        <input className="form-control mb-1" placeholder="Original Key" value={form.original_key}
          onChange={e => setForm({ ...form, original_key: (e.target as HTMLInputElement).value })} />
        <input className="form-control" placeholder="Original Author" value={form.original_author}
          onChange={e => setForm({ ...form, original_author: (e.target as HTMLInputElement).value })} />
      </div>
      <button className="btn btn-primary" onClick={create}>Add</button>
    </div>
  );
}
