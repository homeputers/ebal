import { useEffect, useState } from 'react';
import { Navigate } from 'react-router-dom';

interface Category { id: number; name: string; description?: string; }

export default function SongCategories({ token }: { token: string }) {
  const [categories, setCategories] = useState<Category[]>([]);
  const [name, setName] = useState('');
  const [description, setDescription] = useState('');
  const [edit, setEdit] = useState<Category | null>(null);
  const headers = { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json' };

  const load = () => {
    fetch('/api/song-categories', { headers })
      .then(res => res.json())
      .then(setCategories);
  };
  useEffect(load, []);

  const create = () => {
    fetch('/api/song-categories', { method: 'POST', headers, body: JSON.stringify({ name, description }) })
      .then(() => { setName(''); setDescription(''); load(); });
  };

  const update = () => {
    if (!edit) return;
    fetch('/api/song-categories/' + edit.id, { method: 'PUT', headers, body: JSON.stringify({ name: edit.name, description: edit.description }) })
      .then(() => { setEdit(null); load(); });
  };

  const remove = (id: number) => {
    fetch('/api/song-categories/' + id, { method: 'DELETE', headers }).then(load);
  };

  if (!token) return <Navigate to="/login" />;

  return (
    <div className="container">
      <h2 className="mb-3">Song Categories</h2>
      <ul className="list-group mb-3">
        {categories.map(c => (
          <li key={c.id} className="list-group-item d-flex justify-content-between">
            {edit && edit.id === c.id ? (
              <span className="w-100">
                <input className="form-control mb-1" value={edit.name}
                  onChange={e => setEdit({ ...edit, name: (e.target as HTMLInputElement).value })} />
                <input className="form-control" value={edit.description || ''}
                  onChange={e => setEdit({ ...edit, description: (e.target as HTMLInputElement).value })} />
              </span>
            ) : (
              <span>
                <strong>{c.name}</strong> {c.description}
              </span>
            )}
            <span>
              {edit && edit.id === c.id ? (
                <>
                  <button className="btn btn-sm btn-primary me-2" onClick={update}>Save</button>
                  <button className="btn btn-sm btn-secondary" onClick={() => setEdit(null)}>Cancel</button>
                </>
              ) : (
                <>
                  <button className="btn btn-sm btn-secondary me-2" onClick={() => setEdit(c)}>Edit</button>
                  <button className="btn btn-sm btn-danger" onClick={() => remove(c.id)}>Delete</button>
                </>
              )}
            </span>
          </li>
        ))}
      </ul>
      <h4>Add Category</h4>
      <div className="mb-2">
        <input className="form-control mb-1" placeholder="Name" value={name}
          onChange={e => setName((e.target as HTMLInputElement).value)} />
        <input className="form-control" placeholder="Description" value={description}
          onChange={e => setDescription((e.target as HTMLInputElement).value)} />
      </div>
      <button className="btn btn-primary" onClick={create}>Add</button>
    </div>
  );
}
