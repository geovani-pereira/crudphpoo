<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Models\Compra;



class ComprasController extends Controlle{


	public function index(ServerRequestInterface $request, ResponseInterface $response)
	{
		$compras = Compra::all();

	$this->compras = $compras;

	
    return $this->view('compras/index',$response);

	}

	public function detalhe(ServerRequestInterface $request, ResponseInterface $response)
	{
		$id = $request->getAttribute('id');
		$compra = Compra::find($id);

		$this->compra = $compra;

	
    	return $this->view('compras/show',$response);

	}


public function adicionar(ServerRequestInterface $request, ResponseInterface $response)
	{
		
    return $this->view('compras/create',$response);

	}

public function salvar(ServerRequestInterface $request, ResponseInterface $response)
	{
		$dados = $request->getParsedBody();
		$compra = new Compra;
		$compra->titulo = $dados['titulo'];
		$compra->desc = $dados['desc'];
 		$objCompra = $compra->save();	

		if ($objCompra){
			//Sucesso
		}else {
			//Erro
		}
		return $response->withRedirect('/compras');
	}

	public function editar(ServerRequestInterface $request, ResponseInterface $response)
	{
		$id = $request->getAttribute('id');
		$compra = Compra::find($id);
		$this->compra = $compra;
		
    return $this->view('compras/edit',$response);

	}

public function atualizar(ServerRequestInterface $request, ResponseInterface $response)
	{
		$dados = $request->getParsedBody();
		$id = $request->getAttribute('id');
		$compra = Compra::find($id);
		//var_dump($compra);exit;
		//$compra = new Compra;
		

		$compra->titulo = $dados['titulo'];
		$compra->desc = $dados['desc'];
 		$objCompra = $compra->save();	

		if ($objCompra){
			//Sucesso
		}else {
			//Erro
		}
		return $response->withRedirect('/compras');
	}

	public function deletar(ServerRequestInterface $request, ResponseInterface $response)
	{
		$id = $request->getAttribute('id');
		$compra = Compra::find($id); //buscando a compra
		//var_dump($compra);exit;
		//$compra = new Compra;
		

		
 		$ok = $compra->delete();	

		if ($ok){
			//Sucesso
		}else {
			//Erro
		}
		return $response->withRedirect('/compras');
	}
}