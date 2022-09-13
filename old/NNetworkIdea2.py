# Based on: https://towardsdatascience.com/how-to-build-your-own-neural-network-from-scratch-in-python-68998a08e4f6

import numpy as np
from PIL import Image

def sigmoid(t):
    return 1/(1+np.exp(-t))

# Derivative of sigmoid
def sigmoid_derivative(p):
    return p * (1 - p)

class NeuralNetwork:
    def __init__(self, InputShape):
        # ToDo 
        self.weights = [np.random.rand(InputShape[1],4), np.random.rand(4,1)]
    
    def feedforward(self, Input):
        lastOutput = Input
        self.layerStates = []
        for weight in self.weights:
            lastOutput = sigmoid(np.dot(lastOutput, weight))
            self.layerStates.append(lastOutput)

        return lastOutput
    
    def backprop(self, Input, Output):
        # ToDo
        lastOutput  = self.feedforward(Input)

        d_weights1 = np.dot(
            self.layerStates[0].T, #Uebergeordnetes Layer
            (2*(Output - lastOutput) * sigmoid_derivative(lastOutput)) #np.dot(2*(Output - lastOutput) * sigmoid_derivative(lastOutput), ...)
        )
        d_weights0 = np.dot(
            Input.T, #Uebergeordnetes Layer
            (np.dot(2*(Output - lastOutput) * sigmoid_derivative(lastOutput), self.weights[1].T) * sigmoid_derivative(self.layerStates[0]))
        )

        self.weights[0] += d_weights0
        self.weights[1] += d_weights1

# ToDo: Multiple Inputs to train

Input = np.array(
    ([0, 1, 1],
    [0, 0, 1],
    [1, 0, 1],
    [0, 0, 1]),
    dtype=float
)

Outputs = np.array(([1], [0], [1], [0]), dtype=float)

#Input2 = np.array(
#    ([1, 1, 0],
#    [0, 1, 1],
#    [1, 0, 0],
#    [0, 0, 1]),
#    dtype=float
#)

#Outputs2 = np.array(([1], [1], [0], [0]), dtype=float)

NNetwork = NeuralNetwork(Input.shape)
for i in range(1500):
    NNetwork.backprop(Input, Outputs)

print(NNetwork.feedforward(Input))

with Image.open("dataset/sqare.png") as im:
    pix = np.array(im.getdata()).reshape(im.size[0], im.size[1], 3)
    print(pix)