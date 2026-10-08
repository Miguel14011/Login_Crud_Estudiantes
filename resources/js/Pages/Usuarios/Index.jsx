import { Head, router, usePage } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';

export default function Index({ usuarios }) {
    const { auth, errors } = usePage().props;

    const cambiarRol = (usuario, role) => {
        router.patch(`/usuarios/${usuario.id}/rol`, { role }, { preserveScroll: true });
    };

    return (
        <AppLayout title="Usuarios">
            <Head title="Usuarios" />

            {errors.role && (
                <div className="mb-4 rounded border border-red-300 bg-red-50 px-4 py-3 text-red-800">{errors.role}</div>
            )}

            <div className="overflow-x-auto rounded-lg bg-white shadow">
                <table className="w-full text-left text-sm">
                    <thead className="bg-slate-50 text-xs uppercase text-slate-500">
                        <tr>
                            <th className="px-4 py-3">Nombre</th>
                            <th className="px-4 py-3">Usuario</th>
                            <th className="px-4 py-3">Email</th>
                            <th className="px-4 py-3">Registrado</th>
                            <th className="px-4 py-3">Rol</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100">
                        {usuarios.map((u) => (
                            <tr key={u.id} className="hover:bg-slate-50">
                                <td className="px-4 py-3 font-medium">{u.name}</td>
                                <td className="px-4 py-3">{u.username}</td>
                                <td className="px-4 py-3">{u.email}</td>
                                <td className="px-4 py-3">{new Date(u.created_at).toLocaleDateString('es-CO')}</td>
                                <td className="px-4 py-3">
                                    {u.id === auth.user.id ? (
                                        <span className="text-slate-500">Administrador (tú)</span>
                                    ) : (
                                        <select
                                            value={u.role}
                                            onChange={(e) => cambiarRol(u, e.target.value)}
                                            className="rounded border border-slate-300 bg-white px-2 py-1"
                                        >
                                            <option value="usuario">Usuario (solo lectura)</option>
                                            <option value="admin">Administrador</option>
                                        </select>
                                    )}
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </AppLayout>
    );
}
