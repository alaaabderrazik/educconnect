@extends('layouts.dashboard')

@section('title', 'Gestion des FAQ')
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Questions Fréquentes (FAQ) ❓</h1>
            <p class="text-slate-500 text-sm mt-1">Gérez les questions-réponses affichées sur le site public.</p>
        </div>
        <button onclick="document.getElementById('addFAQModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-black text-sm transition-all shadow-xl shadow-blue-500/20 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Nouvelle Question
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3 animate-fade-in text-sm font-bold">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
    @endif

    <div class="space-y-4">
        @forelse($faqs as $faq)
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-start gap-6 group hover:border-blue-200 transition-colors">
            <div class="w-10 h-10 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <span class="font-black text-xs">#{{ $faq->order }}</span>
            </div>
            <div class="flex-grow">
                <h3 class="font-bold text-slate-900 mb-2">{{ $faq->question }}</h3>
                <p class="text-slate-500 text-sm leading-relaxed">{{ $faq->answer }}</p>
            </div>
            <div class="flex gap-2">
                <button onclick="openEditModal(this)" data-faq="{{ json_encode($faq) }}" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-blue-600 hover:text-white transition-all flex items-center justify-center">
                    <i class="fa-solid fa-pen text-xs"></i>
                </button>
                <form action="{{ route('dashboard.admin.destroy', ['type' => 'faq', 'id' => $faq->id]) }}" method="POST" onsubmit="return confirm('Supprimer cette FAQ ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="py-12 text-center text-slate-400 italic bg-white rounded-3xl border border-slate-100 shadow-sm">
            Aucune FAQ configurée.
        </div>
        @endforelse
    </div>

    <!-- Add Modal -->
    <div id="addFAQModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl border border-white/20 animate-fade-in-up overflow-hidden">
            <div class="p-8 bg-slate-900 text-white flex justify-between items-center">
                <h3 class="text-xl font-black flex items-center gap-3 uppercase tracking-tighter">
                    <i class="fa-solid fa-question text-blue-400"></i> Nouvelle FAQ
                </h3>
                <button onclick="document.getElementById('addFAQModal').classList.add('hidden')" class="text-white/50 hover:text-white transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form action="{{ route('dashboard.admin.faqs.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Question</label>
                        <input type="text" name="question" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Réponse</label>
                        <textarea name="answer" required rows="4" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm leading-relaxed transition-all"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Ordre d'affichage</label>
                        <input type="number" name="order" value="1" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                    </div>
                </div>
                
                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="document.getElementById('addFAQModal').classList.add('hidden')" class="flex-grow px-8 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-xs uppercase tracking-widest rounded-2xl transition-all"> Annuler </button>
                    <button type="submit" class="flex-grow px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl transition-all shadow-xl shadow-blue-500/20"> Ajouter </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editFAQModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl border border-white/20 animate-fade-in-up overflow-hidden">
            <div class="p-8 bg-blue-600 text-white flex justify-between items-center">
                <h3 class="text-xl font-black flex items-center gap-3 uppercase tracking-tighter">
                    <i class="fa-solid fa-pen"></i> Modifier la FAQ
                </h3>
                <button onclick="document.getElementById('editFAQModal').classList.add('hidden')" class="text-white/50 hover:text-white transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form id="editFAQForm" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Question</label>
                        <input type="text" name="question" id="edit_question" required class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Réponse</label>
                        <textarea name="answer" id="edit_answer" required rows="4" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm leading-relaxed transition-all"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Ordre d'affichage</label>
                        <input type="number" name="order" id="edit_order" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm transition-all">
                    </div>
                </div>
                
                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="document.getElementById('editFAQModal').classList.add('hidden')" class="flex-grow px-8 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black text-xs uppercase tracking-widest rounded-2xl transition-all"> Annuler </button>
                    <button type="submit" class="flex-grow px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black text-xs uppercase tracking-widest rounded-2xl transition-all shadow-xl shadow-blue-500/20"> Sauvegarder </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(button) {
            const faq = JSON.parse(button.dataset.faq);
            const form = document.getElementById('editFAQForm');
            form.action = `/dashboard/admin/faqs/${faq.id}`;
            
            document.getElementById('edit_question').value = faq.question;
            document.getElementById('edit_answer').value = faq.answer;
            document.getElementById('edit_order').value = faq.order;
            
            document.getElementById('editFAQModal').classList.remove('hidden');
        }
    </script>
@endsection
