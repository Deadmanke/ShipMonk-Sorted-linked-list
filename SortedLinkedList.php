<?php

/**
 * Sorted linked list class.
 */
class SortedLinkedList {

    const TYPE_INT = 'integer';
    const TYPE_STRING = 'string';

    /**
     * @var string
     */
    private $__type;

    /**
     * @var Node
     */
    private $__head;

    /**
     * Returns the list type (integer OR string).
     *
     * @return string
     */
    public function getType()
    {
        return $this->__type;
    }

    /**
     * Sets and validates the list type.
     *
     * @param string $type
     * @return void
     * @throws Exception
     */
    private function __setType(string $type)
    {
        if (!$this->__isValidType($type)) {
            throw new Exception('Invalid value. Only integer and string values can be entered.');
        }
        $this->__type = $type;
    }

    /**
     * Returns whether the list type is valid or not.
     *
     * @param string $type
     * @return bool
     */
    private function __isValidType(string $type)
    {
        return in_array($type, $this->__getValidTypes());
    }

    /**
     * Returns whether the specified list type is the same as currently set.
     *
     * @param string $type
     * @return bool
     */
    private function __isValidDataType(string $type)
    {
        if ($this->isEmpty()) {
            return true;
        }

        return $this->getType() == $type;
    }

    /**
     * Returns possible list types.
     *
     * @return string[]
     */
    private function __getValidTypes()
    {
        return [
            self::TYPE_INT,
            self::TYPE_STRING
        ];
    }

    /**
     * @return Node
     */
    private function __getHead()
    {
        return $this->__head;
    }

    /**
     * @param Node $_head
     */
    private function __setHead(Node $_head)
    {
        $this->__head = $_head;
    }

    /**
     * Returns whether the list is empty or not.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->__head === null;
    }

    /**
     * Inserting a value into a list.
     *
     * @param mixed $value
     * @return void
     * @throws Exception
     */
    public function insert($value)
    {
        $valueType = gettype($value);

        if (!$this->getType()) {
            $this->__setType($valueType);
        }

        if (!$this->__isValidDataType($valueType)) {
            throw new Exception(sprintf('Invalid value. At this moment, only "%s" values can be entered', $this->getType()));
        }

        $newNode = new Node($value);

        if ($this->isEmpty()) {
            $this->__setHead($newNode);
        } else if ($value <= $this->__getHead()->getValue()) {
            $newNode->setNext($this->__getHead());
            $this->__setHead($newNode);
        } else {
            $current = $this->__getHead();
            while ($current->getNext() && $current->getNext()->getValue() < $value) {
                $current = $current->getNext();
            }
            if ($current->getNext()) {
                $newNode->setNext($current->getNext());
            }
            $current->setNext($newNode);
        }
    }

    /**
     * Searching for a value in a list.
     *
     * @param mixed $value
     * @return bool
     */
    public function search($value)
    {
        $current = $this->__getHead();
        while ($current) {
            if ($current->getValue() === $value) {
                return true;
            }
            $current = $current->getNext();
        }
        return false;
    }

    /**
     * Deleting a value from a list.
     *
     * @param mixed $value
     * @return void
     */
    public function delete($value)
    {
        if ($this->isEmpty()) {
            return;
        }

        $current = $this->__getHead();
        while ($current->getValue() === $value) {
            if (!$current->getNext()) {
                $this->clear();
                return;
            }
            $this->__setHead($current->getNext());
            $current = $current->getNext();
        }

        while ($current->getNext()) {
            if ($current->getNext()->getValue() === $value) {
                if ($current->getNext()->getNext()) {
                    $current->setNext($current->getNext()->getNext());
                    continue;
                }
                $current->deleteNext();
                break;
            }

            $current = $current->getNext();
        }
    }

    /**
     * Listing the values of a list.
     *
     * @return void
     */
    public function print()
    {
        $current = $this->__getHead();
        while ($current) {
            if ($current !== $this->__getHead()) {
                printf(' -> ');
            }
            printf('%s', $current->getValue());
            $current = $current->getNext();
        }
    }

    /**
     * Return a list as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $elements = [];
        $current = $this->__getHead();
        while ($current) {
            $elements[] = $current->getValue();
            $current = $current->getNext();
        }
        return $elements;
    }

    /**
     * Returns the number of items in the list.
     *
     * @return int
     */
    public function count()
    {
        $result = 0;
        $current = $this->__getHead();
        while ($current) {
            $result++;
            $current = $current->getNext();
        }
        return $result;
    }

    /**
     * Resets the contents of the sheet.
     *
     * @return void
     */
    public function clear()
    {
        $this->__head = null;
        $this->__type = null;
    }
}

class Node {

    /**
     * @var mixed
     */
    private $__value;

    /**
     * @var Node
     */
    private $__next;

    /**
     * @param $value
     */
    public function __construct($value) {
        $this->__value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->__value;
    }

    /**
     * @param mixed $_value
     */
    public function setValue($_value)
    {
        $this->__value = $_value;
    }

    /**
     * @return Node
     */
    public function getNext()
    {
        return $this->__next;
    }

    /**
     * @param Node $_next
     */
    public function setNext(Node $_next)
    {
        $this->__next = $_next;
    }

    /**
     * @return void
     */
    public function deleteNext()
    {
        $this->__next = null;
    }
}

try {

    $list = new SortedLinkedList();
    $list->insert(1);
    $list->insert(2);
    $list->insert(3);
    $list->insert(2);
    $list->insert(5);
    $list->insert(8);
    $list->insert(4);
    $list->insert(0);
    $list->insert(-5);
    $list->insert(-2);

    $list->print();

} catch (Exception $e) {
    print_r($e->getMessage());
}
