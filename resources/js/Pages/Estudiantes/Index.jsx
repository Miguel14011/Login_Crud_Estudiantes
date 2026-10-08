import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import AppLayout from '../../Layouts/AppLayout';

export default function Index({ estudiantes, filtros }) {
    const [buscar, setBuscar] = useState(filtros.buscar ?? '');

    const filtrar = (e) => {
        e.preventDefault();
        router.get('/estudiantes', { buscar }, { preserveState: true, replace: true });
    };

    const eliminar = (estudiante) => {
        if (window.confirm(`¿Eliminar a ${estudiante.nombre} ${estudiante.apellido}?`)) {
            router.delete(`/estudiantes/${estudiante.id}`, { preserveScroll: true });
        }
    };

    return (
        <AppLayout
            title="Estudiantes"
            actions={
                <Link
                    href="/estudiantes/create"
                    className="rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                >
                    + Nuevo estudiante
                </Link>
            }
        >
            <Head title="Estudiantes" />

            <form onSubmit={filtrar} className="mb-4 flex gap-2">
                <input
                    type="search"
                    placeholder="Buscar por nombre, email o carrera…"
                    value={buscar}
                    onChange={(e) => setBuscar(e.target.value)}
                    className="w-full max-w-sm rounded border border-slate-300 bg-white px-3 py-2"
                />
                <button className="rounded border border-slate-300 bg-white px-4 py-2 hover:bg-slate-50">Buscar</button>
            </form>

            <div className="overflow-x-auto rounded-lg bg-white shadow">
                <table className="w-full text-left text-sm">
                    <thead className="bg-slate-50 text-xs uppercase text-slate-500">
                        <tr>
                            <th className="px-4 py-3">Nombre</th>
                            <th className="px-4 py-3">Email</th>
                            <th className="px-4 py-3">Carrera</th>
                            <th className="px-4 py-3 text-center">Semestre</th>
                            <th className="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100">
                        {estudiantes.data.length === 0 && (
                            <tr>
                                <td colSpan="5" className="px-4 py-8 text-center text-slate-500">
                                    No hay estudiantes registrados.
                                </td>
                            </tr>
                        )}
                        {estudiantes.data.map((e) => (
                            <tr key={e.id} className="hover:bg-slate-50">
                                <td className="px-4 py-3 font-medium">
                                    {e.nombre} {e.apellido}
                                </td>
                                <td className="px-4 py-3">{e.email}</td>
                                <td className="px-4 py-3">{e.carrera}</td>
                                <td className="px-4 py-3 text-center">{e.semestre}</td>
                                <td className="space-x-3 whitespace-nowrap px-4 py-3 text-right">
                                    <Link href={`/estudiantes/${e.id}`} className="text-slate-600 hover:underline">
                                        Ver
                                    </Link>
                                    <Link href={`/estudiantes/${e.id}/edit`} className="text-indigo-600 hover:underline">
                                        Editar
                                    </Link>
                                    <button onClick={() => eliminar(e)} className="text-red-600 hover:underline">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>

            {estudiantes.last_page > 1 && (
                <div className="mt-4 flex flex-wrap gap-1">
                    {estudiantes.links.map((link, i) => (
                        <Link
                            key={i}
                            href={link.url ?? ''}
                            preserveScroll
                            disabled={!link.url}
                            className={`rounded border px-3 py-1 text-sm ${
                                link.active ? 'border-indigo-600 bg-indigo-600 text-white' : 'bg-white'
                            } ${!link.url ? 'pointer-events-none opacity-40' : ''}`}
                        >
                            {link.label}
                        </Link>
                    ))}
                </div>
            )}
        </AppLayout>
    );
}
