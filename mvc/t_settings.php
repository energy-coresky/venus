<?php

class t_settings extends Model_t
{
    protected $table = 'memory';
    public $cfg;//sky sql "insert into memory values(null,'palette',null,0,null,'',null)" w venus

    function head_y() {
		$dd = $this->t_venus->head_y();
		list($id, $tmemo) = $dd->sqlf('+select id, tmemo from memory where name=%s union select 0, ""', $this->_2);
		if (null !== $tmemo)
			$this->cfg = SKY::ghost('t', $tmemo, 'update $_memory set dt=$now, tmemo=%s where id=' . $id, 0, $dd);
        return $dd;
    }

	function _all() {
        if ($_POST) {
            $this->update(['tmemo' => $_POST['ta']], 99);
            SKY::w('tailwind', $_POST['tw']);
        }
        return [
            'ta' => $this->cell(99, 'tmemo'),
            'form' => [],
        ];
	}

	function _tcolors() {
        return [
            'form' => [],
        ];
	}

	function _hcolors() {
        return [
            'form' => [],
        ];
	}

	function _palette() {
        return [
            'form' => [],
        ];
	}

	function _ruler() {
        return [
            'form' => [],
        ];
	}

	function _box() {
        return [
            'form' => [],
        ];
	}

	function _css() {
        return [
            'form' => [],
        ];
	}

	function _text() {
        return [
            'form' => [],
        ];
	}

	function _pseudo() {
        return [
            'form' => [],
        ];
	}

	function _unicode() {
        return [
            'form' => [],
        ];
	}

	function _icons() {
        return [
            'form' => [],
        ];
	}

	function _() {
        return [
            'form' => [],
        ];
	}
}
