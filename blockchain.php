<?php

class Block {
    public $index;
    public $timestamp;
    public $data;
    public $previousHash;
    public $hash;

    public function __construct($index, $data, $previousHash) {
        $this->index = $index;
        $this->timestamp = time();
        $this->data = $data;
        $this->previousHash = $previousHash;
        $this->hash = $this->calculateHash();
    }

    public function calculateHash() {
        return hash('sha256', $this->index . $this->timestamp . json_encode($this->data) . $this->previousHash);
    }
}

class Blockchain {
    public $chain;

    public function __construct() {
        $this->chain = [];
        $this->loadChain();

        // If chain is still empty, create genesis block
        if (empty($this->chain)) {
            $this->chain[] = $this->createGenesisBlock();
            $this->saveChain();
        }
    }

    private function createGenesisBlock() {
        return new Block(0, "Genesis Block", "0");
    }

    public function getLastBlock() {
        return end($this->chain); // Safely gets last element
    }

    public function addBlock($data) {
        $lastBlock = $this->getLastBlock();

        if (!$lastBlock) {
            $lastBlock = $this->createGenesisBlock();
        }

        $newBlock = new Block(count($this->chain), $data, $lastBlock->hash);
        $this->chain[] = $newBlock;
        $this->saveChain();
    }

    public function saveChain() {
        file_put_contents("blockchain.json", json_encode($this->chain, JSON_PRETTY_PRINT));
    }

    public function loadChain() {
        if (file_exists("blockchain.json")) {
            $data = json_decode(file_get_contents("blockchain.json"), true);
            $this->chain = [];

            foreach ($data as $blockData) {
                $block = new Block(
                    $blockData['index'],
                    $blockData['data'],
                    $blockData['previousHash']
                );
                $block->timestamp = $blockData['timestamp'];
                $block->hash = $blockData['hash'];
                $this->chain[] = $block;
            }
        }
    }

    public function isValid() {
        for ($i = 1; $i < count($this->chain); $i++) {
            $current = $this->chain[$i];
            $previous = $this->chain[$i - 1];

            if ($current->hash !== $current->calculateHash()) return false;
            if ($current->previousHash !== $previous->hash) return false;
        }
        return true;
    }
}
