<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Category
 *
 * @ORM\Table(name="category", indexes={@ORM\Index(name="parent_id_key", columns={"parent_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\CategoryRepository")
 *
 * @Annotation\Name("category")
 * @Annotation\Attributes({"class":"form-horizontal"})
 */
class Category
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Annotation\Exclude()
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", precision=0, scale=0, nullable=true, unique=false)
     *
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Attributes({"class":"form-control", "id":"parentId"})
     * @Annotation\Options({
     *     "label":"Parent",
     *     "label_attributes":{
     *         "class":"control-label col-sm-2"
     *     },
     *     "empty_option":"Select parent category or don't select nothing, then it will be new category",
     *     "target_class":"Application\Entity\Category",
     *     "property":"name"
     * })
     */
    private $parentId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, precision=0, scale=0, nullable=false, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Attributes({"class":"form-control", "id":"name", "required":"required"})
     * @Annotation\Required({"required":"true"})
     * @Annotation\Options({
     *     "label":"Name",
     *     "label_attributes":{
     *         "class":"control-label col-sm-2"
     *     },
     *     "min":"2",
     *     "max":"100"
     * })
     * @Annotation\Filter({"name":"stripTags", "name":"stringTrim"})
     * @Annotation\Validator({
     *     "name":"stringLength",
     *     "options":{
     *         "encoding":"utf-8",
     *         "min":"2",
     *         "max":"100"
     *     }
     * })
     * @Annotation\AllowEmpty({"allowempty":"true"})
     */
    private $name;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"class":"btn btn-default", "value":"Submit"})
     * @Annotation\AllowEmpty({"allowempty":"true"})
     */
    private $submit;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set parentId
     *
     * @param integer $parentId
     *
     * @return Category
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return integer
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

