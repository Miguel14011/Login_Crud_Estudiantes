import { Head, Link } from '@inertiajs/react';

const mensajes = {
    403: ['Acceso denegado', 'No tienes permiso para realizar esta acción. Solo un administrador puede hacerlo.'],
    404: ['Página no encontrada', 'El recurso que buscas no existe o fue eliminado.'],
};

export default function Error({ status }) {
    const [titulo, descripcion] = mensajes[status] ?? ['Error', 'Ocurrió un error inesperado.'];

    return (
        <div className="flex min-h-screen items-center justify-center px-4">
            <Head title={titulo} />
            <div className="w-full max-w-md rounded-lg bg-white p-8 text-center shadow">
                <p className="text-5xl font-bold text-indigo-600">{status}</p>
                <h1 className="mt-3 text-xl font-semibold">{titulo}</h1>
                <p className="mt-2 text-slate-500">{descripcion}</p>
                <Link
                    href="/estudiantes"
                    className="mt-6 inline-block rounded bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                >
                    Volver al listado
                </Link>
            </div>
        </div>
    );
}
