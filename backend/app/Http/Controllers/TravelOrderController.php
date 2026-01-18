<?php

namespace App\Http\Controllers;

use App\Models\TravelOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Notifications\TravelOrderStatusChanged;

class TravelOrderController extends Controller
{
    
    public function index(Request $request)
    {
        $query = TravelOrder::query();

        // Se não for admin, só enxerga os próprios pedidos
        if (!auth()->user()->is_admin) {
            $query->where('user_id', auth()->id());
        }

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('destination')) {
            $query->where('destination', 'like', "%{$request->destination}%");
        }

        if ($request->filled('from_date')) {
            $query->whereDate('departure_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('return_date', '<=', $request->to_date);
        }

        return response()->json(
            $query->orderByDesc('id')->get()
        );
    }


    // Cria uma nova ordem de viagem
    public function store(Request $request)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'destination'     => 'required|string|max:255',
            'departure_date'  => 'required|date',
            'return_date'     => 'required|date|after_or_equal:departure_date',
        ]);
     
        // retorna erro de validação
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        $order = auth()->user()->travelOrders()->create([
            'destination'    => $data['destination'],
            'departure_date' => $data['departure_date'],
            'return_date'    => $data['return_date'],
            'status'         => 'solicitado',
        ]);

        return response()->json($order, 201);
    }

    // Busca uma ordem de viagem pelo ID
    public function show($id)
    {
        $order = TravelOrder::findOrFail($id);

        // Se não for admin, só pode ver o próprio pedido
        if (!auth()->user()->is_admin && $order->user_id !== auth()->id()) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        return response()->json($order);
    }



    // Atualiza o status(situação) de uma ordem de viagem (somente admin pode fazer isso)
    public function updateStatus(Request $request, $id)
    {
        $order = TravelOrder::findOrFail($id);

        if (!auth()->user()->is_admin) {
            return response()->json(['error' => 'Apenas um administrador pode alterar a situação de um pedido.'], 403);
        }

        $request->validate([
            'status' => 'required|in:aprovado,cancelado'
        ]);

        // Evita mudança para o mesmo status
        if ($order->status === $request->status) {
            return response()->json([
                'error' => 'O pedido já está com essa situação.'
            ], 422);
        }

        // Um pedido aprovado não pode ser cancelado
        if ($order->status === 'aprovado' && $request->status === 'cancelado') {
            return response()->json([
                'error' => 'Pedidos aprovados não podem ser cancelados.'
            ], 422);
        }

        $order->status = $request->status;
        $order->save();

        // Notifica o usuário sobre a mudança de status
        if ($order->user) {
            $order->user->notify(new TravelOrderStatusChanged($order));
        }


        return response()->json($order);
    }

}
