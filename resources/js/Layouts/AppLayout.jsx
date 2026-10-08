import { Link, usePage } from '@inertiajs/react';

export default function AppLayout({ title, actions, children }) {
    const { auth, flash } = usePage().props;

    return (
        <div className="min-h-screen">
            <nav className="bg-indigo-700 text-white shadow">
                <div className="mx-auto flex max-w-5xl items-center justify-between px-4 py-3">
                    <Link href="/estudiantes" className="text-lg font-semibold">
                        Gestión de Estudiantes
                    </Link>
                    <div className="flex items-center gap-4 text-sm">
                        <span>
                            Hola, <strong>{auth.user?.name}</strong>
                        </span>
                        <Link
                            href="/logout"
                            method="post"
                            as="button"
                            className="rounded bg-white/15 px-3 py-1.5 hover:bg-white/25"
                        >
                            Cerrar sesión
                        </Link>
                    </div>
                </div>
            </nav>

            <main className="mx-auto max-w-5xl px-4 py-8">
                <div className="mb-6 flex flex-wrap items-center justify-between gap-3">
                    <h1 className="text-2xl font-semibold">{title}</h1>
                    {actions}
                </div>

                {flash?.success && (
                    <div className="mb-6 rounded border border-green-300 bg-green-50 px-4 py-3 text-green-800">
                        {flash.success}
                    </div>
                )}

                {children}
            </main>
        </div>
    );
}
