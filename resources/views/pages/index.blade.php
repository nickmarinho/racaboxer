@extends('layout.default')

@section('title', 'Página Inicial - ')
@section('description', 'Esta é a página inicial do site')
@section('keywords', 'página, inicial, raca, raça, boxer')

@section('content')

				<div class="col-md-12">
					<div class="col-md-12 title-bar">
						<div class="col-md-1 title-bar-left"></div>
						<div class="col-md-11 title-bar-right"></div>
					</div>
					<div class="clearfix"></div>
					<div class="col-md-12">
						<p>Esse lindo cão é mais um dos que precisa de sua ajuda.<br />Para saber como adotá-lo clique aqui.<br /><br />Adote um cãozinho, não compre!!!</p>
						<p><img alt="Adote um cão" src="/images/adote-um-cao.png" class="dog-picture"></p>
					</div>
					<div class="clearfix"></div>
					<div class="col-md-12 bottom-button">
						<a href="/doacoes" title="VEJA A LISTA PARA ADOÇÃO">LISTA DE ADOÇÃO</a>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div>

@stop