<?php

namespace Aot\MivarTextSemantic;

/**
 * class MivarSpaceWdw
 * @author Eliseev
 * @brief Класс для описания миварного пространства, состоящего из набора точек wdw
 *
 */

class MivarSpaceWdw extends MivarSpace
{
    protected $space_kw = array();
    /**< массив точек пространства, сгруппированных по kw (индекс по kw)*/

    /**
     * @brief Добавление новой точки в пространство с созданием объекта класса PointWdw
     *
     * @param $kw - номер слова в предложении
     * @param $ks - номер предложения в тексте
     * @param $count_dw - количество вариантов морфологических признаков из словаря
     * @param $w - слово: объект класса Word
     * @param $dw - словарная статья: объект класса Dw
     * @return ссылка на объект
     */

    public function add_point_wdw($kw, $ks, $count_dw, $w, $dw)
    {
        return $this->add_point(new PointWdw($kw, $ks, $count_dw, $w, $dw));
    }

    /**
     * @brief Добавление новой точки в пространство (Перегрузка родительского метода)
     *
     * @param $point - точка - объект класса PointWdw или любого, отнаследованного от него
     * @return ссылка на объект
     */

    public function add_point($point = null)
    {
        /*echo "<pre>";
        print_r($point);
        echo "</pre>";*/
        if ($point instanceof PointWdw && isset($point->kw)) {
            parent::add_point($point);
            $this->space_kw[$point->kw][$point->key_point] = $point;
        }
        return $this;
    }

    /**
     * @brief Получение точек пространства по индексу слова в предложении
     *
     * @param $index - индекс слова в предложении
     * @return массив точек пространства
     */

    public function get_space_kw_item($index)
    {
        return (isset($this->space_kw[$index])) ? $this->space_kw[$index] : array();
    }

    /**
     * @brief Получение пространства с индексом $space_kw по kw
     *
     * @return пространства с индексом $space_kw
     */

    public function get_space_kw()
    {
        return $this->space_kw;
    }

    /**
     * @brief Записать пространство в хмл
     * @param $parent_node - родительский узел
     * @return ссылка на объект
     */

    /*  public function to_xml($parent_node)
      {
          $node_space_wdw = $parent_node->appendChild(new \DOMElement('space_wdw'));
          foreach ($this->get_space() as $point_wdw) {
              $node_point_wdw = $node_space_wdw->appendChild(new \DOMElement('point_wdw'));
              add_array_to_xml($node_point_wdw, $point_wdw, null, false);
              add_array_to_xml($node_point_wdw->appendChild(new \DOMElement('w')), $point_wdw->w);
              $node_dw = $node_point_wdw->appendChild(new \DOMElement('dw'));
              $node_parameters = $node_dw->appendChild(new \DOMElement('parameters'));
              add_array_to_xml($node_dw, $point_wdw->dw, null, false);
              $array_param = array();
              foreach ($point_wdw->dw->parameters as $param) {
                  $node_parameter = $node_parameters->appendChild(new \DOMElement('parameter'));
                  $array_param[] = "<p>{$param->name}</p>" . ' - ' . implode(", ", $param->short_value);
                  foreach ($param as $key => $val) {
                      $val_param = (is_array($val)) ? $val_param = implode(", ", $val) : $val;
                      $node_parameter->setAttribute($key, $val_param);
                  }
              }
              $node_parameters->setAttribute('param_values', implode('; ', $array_param));
          }
          return $node_space_wdw;
      }*/
}