import { useEffect, useState } from 'react';
import { Navigate } from 'react-router-dom';

interface Member {
  id: number;
  name: string;
  email?: string;
  phone?: string;
}

export default function Members({ token }: { token: string }) {
  const [members, setMembers] = useState<Member[]>([]);
  const [form, setForm] = useState<Omit<Member, 'id'>>({ name: '', email: '', phone: '' });
  const [edit, setEdit] = useState<Member | null>(null);
  const headers = { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json' };

  const load = () => {
    fetch('/api/members', { headers })
      .then(res => res.json())
      .then(setMembers);
  };

  useEffect(load, []);

  const create = () => {
    fetch('/api/members', { method: 'POST', headers, body: JSON.stringify(form) })
      .then(() => { setForm({ name: '', email: '', phone: '' }); load(); });
  };

  const update = () => {
    if (!edit) return;
    fetch('/api/members/' + edit.id, {
      method: 'PUT',
      headers,
      body: JSON.stringify({ name: edit.name, email: edit.email, phone: edit.phone })
    }).then(() => { setEdit(null); load(); });
  };

  const remove = (id: number) => {
    fetch('/api/members/' + id, { method: 'DELETE', headers }).then(load);
  };

  if (!token) return <Navigate to="/login" />;

  return (
    <div className="container">
      <h2 className="mb-3">Members</h2>
      <ul className="list-group mb-3">
        {members.map(m => (
          <li key={m.id} className="list-group-item d-flex justify-content-between">
            {edit && edit.id === m.id ? (
              <span className="w-100">
                <input className="form-control mb-1" value={edit.name}
                  onChange={e => setEdit({ ...edit, name: (e.target as HTMLInputElement).value })} />
                <input className="form-control mb-1" value={edit.email || ''}
                  onChange={e => setEdit({ ...edit, email: (e.target as HTMLInputElement).value })} />
                <input className="form-control" value={edit.phone || ''}
                  onChange={e => setEdit({ ...edit, phone: (e.target as HTMLInputElement).value })} />
              </span>
            ) : (
              <span>
                <strong>{m.name}</strong> {m.email} {m.phone}
              </span>
            )}
            <span>
              {edit && edit.id === m.id ? (
                <>
                  <button className="btn btn-sm btn-primary me-2" onClick={update}>Save</button>
                  <button className="btn btn-sm btn-secondary" onClick={() => setEdit(null)}>Cancel</button>
                </>
              ) : (
                <>
                  <button className="btn btn-sm btn-secondary me-2" onClick={() => setEdit(m)}>Edit</button>
                  <button className="btn btn-sm btn-danger" onClick={() => remove(m.id)}>Delete</button>
                </>
              )}
            </span>
          </li>
        ))}
      </ul>
      <h4>Add Member</h4>
      <div className="mb-2">
        <input className="form-control mb-1" placeholder="Name" value={form.name}
          onChange={e => setForm({ ...form, name: (e.target as HTMLInputElement).value })} />
        <input className="form-control mb-1" placeholder="Email" value={form.email}
          onChange={e => setForm({ ...form, email: (e.target as HTMLInputElement).value })} />
        <input className="form-control" placeholder="Phone" value={form.phone}
          onChange={e => setForm({ ...form, phone: (e.target as HTMLInputElement).value })} />
      </div>
      <button className="btn btn-primary" onClick={create}>Add</button>
    </div>
  );
}
