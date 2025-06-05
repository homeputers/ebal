import { useEffect, useState } from 'react';
import { Navigate, Link } from 'react-router-dom';

interface Member {
  id: number;
  name: string;
  email?: string;
  phone?: string;
}

export default function Members({ token }: { token: string }) {
  const [members, setMembers] = useState<Member[]>([]);
  const [form, setForm] = useState<Omit<Member, 'id'>>({ name: '', email: '', phone: '' });
  const [editing, setEditing] = useState<number | null>(null);
  const [error, setError] = useState('');
  const headers = { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json' };

  const load = () => {
    fetch('/api/members', { headers })
      .then(res => res.json())
      .then(setMembers);
  };

  useEffect(load, []);

  const submit = async () => {
    setError("");
    if (!form.name.trim()) { setError("Name is required"); return; }
    try {
      const res = await fetch(editing ? `/api/members/${editing}` : "/api/members", {
        method: editing ? "PUT" : "POST",
        headers,
        body: JSON.stringify(form)
      });
      if (!res.ok) {
        const data = await res.json().catch(() => ({}));
        throw new Error(data.message || "Request failed");
      }
      setForm({ name: "", email: "", phone: "" });
      setEditing(null);
      load();
    } catch (err) {
      setError((err as Error).message);
    }
  };

  const edit = (m: Member) => { setForm({ name: m.name, email: m.email || "", phone: m.phone || "" }); setEditing(m.id); };
  const cancel = () => { setEditing(null); setForm({ name: "", email: "", phone: "" }); setError(""); };
  const remove = async (id: number) => { await fetch(`/api/members/${id}`, { method: "DELETE", headers }); load(); };
  if (!token) return <Navigate to="/login" />;

  return (
    <div className="container" style={{ maxWidth: '600px' }}>
      <Link to="/dashboard" className="btn btn-link p-0 mb-2">&laquo; Back</Link>
      <h2 className="mb-3">Members</h2>
      {error && <div className="alert alert-danger">{error}</div>}
      <div className="mb-3">
        <input className="form-control mb-1" placeholder="Name" value={form.name}
          onChange={e => setForm({ ...form, name: (e.target as HTMLInputElement).value })} />
        <input className="form-control mb-1" placeholder="Email" value={form.email}
          onChange={e => setForm({ ...form, email: (e.target as HTMLInputElement).value })} />
        <input className="form-control" placeholder="Phone" value={form.phone}
          onChange={e => setForm({ ...form, phone: (e.target as HTMLInputElement).value })} />
        <div className="mt-2">
          <button className="btn btn-primary" onClick={submit}>{editing ? 'Update' : 'Add'}</button>
          {editing && <button className="btn btn-secondary ms-2" onClick={cancel}>Cancel</button>}
        </div>
      </div>
      <ul className="list-group">
        {members.map(m => (
          <li key={m.id} className="list-group-item d-flex justify-content-between">
            <span>
              <strong>{m.name}</strong> {m.email} {m.phone}
            </span>
            <span>
              <button className="btn btn-sm btn-secondary me-2" onClick={() => edit(m)}>Edit</button>
              <button className="btn btn-sm btn-danger" onClick={() => remove(m.id)}>Delete</button>
            </span>
          </li>
        ))}
      </ul>
    </div>
  );
}
