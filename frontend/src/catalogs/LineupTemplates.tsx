import { useEffect, useState } from 'react';
import { Navigate } from 'react-router-dom';

interface Template { id: number; name: string; }

export default function LineupTemplates({ token }: { token: string }) {
  const [templates, setTemplates] = useState<Template[]>([]);
  const [name, setName] = useState('');
  const [edit, setEdit] = useState<Template | null>(null);
  const headers = { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json' };

  const load = () => {
    fetch('/api/lineup-templates', { headers })
      .then(res => res.json())
      .then(setTemplates);
  };
  useEffect(load, []);

  const create = () => {
    fetch('/api/lineup-templates', { method: 'POST', headers, body: JSON.stringify({ name }) })
      .then(() => { setName(''); load(); });
  };

  const update = () => {
    if (!edit) return;
    fetch('/api/lineup-templates/' + edit.id, { method: 'PUT', headers, body: JSON.stringify({ name: edit.name }) })
      .then(() => { setEdit(null); load(); });
  };

  const remove = (id: number) => {
    fetch('/api/lineup-templates/' + id, { method: 'DELETE', headers }).then(load);
  };

  if (!token) return <Navigate to="/login" />;

  return (
    <div className="container">
      <h2 className="mb-3">Lineup Templates</h2>
      <ul className="list-group mb-3">
        {templates.map(t => (
          <li key={t.id} className="list-group-item d-flex justify-content-between">
            {edit && edit.id === t.id ? (
              <input className="form-control w-75" value={edit.name}
                onChange={e => setEdit({ ...edit, name: (e.target as HTMLInputElement).value })} />
            ) : (
              <span>{t.name}</span>
            )}
            <span>
              {edit && edit.id === t.id ? (
                <>
                  <button className="btn btn-sm btn-primary me-2" onClick={update}>Save</button>
                  <button className="btn btn-sm btn-secondary" onClick={() => setEdit(null)}>Cancel</button>
                </>
              ) : (
                <>
                  <button className="btn btn-sm btn-secondary me-2" onClick={() => setEdit(t)}>Edit</button>
                  <button className="btn btn-sm btn-danger" onClick={() => remove(t.id)}>Delete</button>
                </>
              )}
            </span>
          </li>
        ))}
      </ul>
      <h4>Add Template</h4>
      <div className="mb-2">
        <input className="form-control" placeholder="Name" value={name}
          onChange={e => setName((e.target as HTMLInputElement).value)} />
      </div>
      <button className="btn btn-primary" onClick={create}>Add</button>
    </div>
  );
}
