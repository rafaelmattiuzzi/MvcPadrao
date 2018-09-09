<?php

trait Pagination
{
	private $maxPage = 2;
	private $errorMessage;
	private $namePage = 'page';
	private $maxLinks = 2;
	private $indexPage = 0;
	private $totalRecords = 0;
	private $links = '';


	private $places = [];

	/**
	*** Método que retorna os itens para paginação;
	*** @param $query => recebe a query que vai consultar as informaçoes necessárias, caso não informar nada ele faz um 'select * from' nesta tabela;
	*** @param $binds => caso necessário deve ser criado um array com as informações dos binds;
	**/
	public function paginate(string $query = '', array $binds = null)
	{
		$retorno = array();

		if($query === '') {
			$sql = "SELECT * FROM {$this->table}";
		} else {
			$sql = $query;
		}
		$this->getIndexPage();
		$sql2 =  $sql.' LIMIT '.$this->indexPage.', '.$this->maxPage;

		if($this->fullQuery($sql2, $binds)) {
			$retorno = $this->returnFullQuery;
		}

		if($this->fullQuery($sql, $binds)) {
			$this->totalRecords = count($this->returnFullQuery);
		}

		return $retorno;
	}

	/**
	*** Método que recebe o número máximo de registros apresentados por página;
	*** @param $max => número de registros;
	**/
	public function maxPerPage($max)
	{
		if(!is_int($max) || !is_numeric($max)) {
			$this->errorMessage = 'Informe um valor inteiro para este parâmetro!';
			return false;
		} else {
			$this->maxPage = (int) $max;
			return true;
		}
	}

	/**
	*** Método que recebe o nome do paginador;
	*** @param $namePage => nome do paginador;
	**/
	public function namePage($name)
	{
		if(!is_string($name)) {
			$this->errorMessage = 'O nome do paginador deve ser no formato de texto!';
			return false;
		} else {
			$this->namePage = (string) $name;
			return true;
		}
	}

	/**
	*** Método que define o número de links visíveis a direita e a esquerda do link selecionado;
	*** @param $number => número de links;
	**/
	public function maxLinks($number)
	{
		if(!is_int($number) || !is_numeric($number)) {
			$this->errorMessage = 'Informe um valor inteiro para este parâmetro!';
			return false;
		} else {
			$this->maxLinks = (int) $number;
			return true;
		}
	}

	/**
	*** Método que gera os links da paginação
	**/
	public function createLinks()
	{
		$this->pagingNumberExceeded();
		if($this->totalRecords > $this->maxPage) {
			$this->firstLink();
			$this->previousLink();
			$this->currentLink();
			$this->nextLink();
			$this->lastLink();
		}
		return $this->links;
	}

	/**
	*** Método que retorna o valor atual da paginação;
	**/
	private function getPager()
	{
		$pager = filter_input(INPUT_GET, $this->namePage);
		return (isset($pager) ? (int) $pager : $pager.'1');
	}

	/**
	*** Método que retorna o número da página;
	**/
	private function getIndexPage()
	{
		if((($this->maxPage * $this->getPager()) - $this->maxPage) > 0) {
			return $this->indexPage = (($this->maxPage * $this->getPager()) - $this->maxPage);
		}
		return $this->indexPage = 0;
	}

	/**
	*** Método que retorna o total de páginas que terá a paginação
	**/
	private function totalPages()
	{
		return ceil($this->totalRecords / $this->maxPage);
	}

	/**
	*** Método que fará o redirecionamento sempre para a última página caso o usuário
	*** passe um valor manual na url que não existe;
	**/
	private function pagingNumberExceeded()
	{
		if (($this->getPager() > $this->totalPages() || $this->getPager() < 1) && $this->totalRecords != 0) {
            header("Location: " . $this->ReturnPageValid($this->namePage) . "?" . $this->namePage . "=" . $this->totalPages());
        }
	}

	/**
	*** Método que gera o html para o primeiro item da paginação;
	**/
	private function firstLink()
    {
        $this->links .= "<div class=\"col-sm-12 text-center\"><ul class=\"pagination\">";
        $first = "<li class=\"page-item\"><a class=\"page-link\" href = \"?" . $this->namePage . "=1\">&laquo;</a></li>";
        $this->links .= $first;
    }

    /**
    *** Método que gera o html para o item anterior da paginação atual
    **/
    private function previousLink()
    {
        for ($i = $this->getPager() - $this->maxLinks; $i <= $this->getPager() - 1; $i++) {
            if ($i >= 1) {
                $this->links .= "<li class=\"page-item\"><a class=\"page-link\" href=\"?" . $this->namePage . "=" . $i . "\">" . $i . "</a></li>";
            }
        }
    }
    /**
    *** Método que gera o html para o item atual da paginação
    **/
    private function currentLink()
    {
        $this->links .= "<li class=\"page-item active\"><a class=\"page-link\" href='#'>" . $this->getPager() . " <span class=\"sr-only\">(current)</span></a></li>";
    }
    /**
    *** Método que gera o html para o próximo item da paginação atual
    **/
    private function nextLink()
    {
        for ($i = $this->getPager() + 1; $i <= $this->getPager() + $this->maxLinks; $i++) {
            if ($i <= $this->totalPages()) {
                $this->links .= "<li class=\"page-item\"><a class=\"page-link\" href=\"?" . $this->namePage . "=" . $i . "\">" . $i . "</a></li>";
            }
        }
    }
    /**
    *** Método que gera o html para o último item da paginação
    **/
    private function lastLink()
    {
        $last = "<li class=\"page-item\"><a class=\"page-link\" href=\"?" . $this->namePage . "=" . $this->totalPages() . "\">&raquo;</a></li></ul></div>";
        $this->links .= $last;
    }

    /**
    *** Método que retorna a url com o nome do page, porem sem o valor da paginação;
    **/
    private function ReturnPageValid($namePager)
    {
        $URL = filter_input(INPUT_SERVER, 'HTTP_HOST');
        $url = "http://" . $URL . filter_input(INPUT_SERVER, 'REQUEST_URI');
        $https = filter_input(INPUT_SERVER, 'HTTPS');
        if (isset($https) && $https == 'on') {
            $url = "https://" . $URL . filter_input(INPUT_SERVER, 'REQUEST_URI');
        }
        return substr($url, 0, strpos($url, "?" . $namePager));
    }



}