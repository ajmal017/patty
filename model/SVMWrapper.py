import numpy as np
from sklearn import svm
from sklearn import linear_model
from sklearn import tree
import pandas as pd
import os
import pandas as pd
from sklearn import preprocessing
from sklearn import svm
from sklearn.svm import SVC
import pickle
from sklearn.metrics import *


class SVMWrapper:

    ## stock information for training and testing
    # open, close, high, low volume
    train_data_x = None
    # percentage
    train_data_y = None
    # open, close, high, low volume
    test_data_x = None
    # percentage
    test_data_y = None

    ## the SVM model
    # the model that has been trained
    model = None


    # kernal function for SVM
    # default: rbf
    kernel = 'linear'

    # random seed
    random_state = 1


    def init(self):
        pass


    def train(self):

        # scale data prior to predict
        scaler = preprocessing.StandardScaler()
        train_data_x_scaled = scaler.fit_transform(self.train_data_x)

        # create SVC object
        self.model = svm.SVC(kernel = self.kernel, random_state = self.random_state)

        # fit training data
        self.model.fit(train_data_x_scaled, self.train_data_y)


    def test(self):

        # scale data prior to predict
        scaler = preprocessing.StandardScaler()
        test_data_x_scaled = scaler.fit_transform(self.test_data_x)

        # predict value
        pred_y = self.model.predict(test_data_x_scaled)

        # get predicted volumes
        score = self.model.score(test_data_x_scaled, self.test_data_y)

        # Accuracy = (TP + TN) / (TP + TN + FP + FN)
        accuracy = accuracy_score(self.test_data_y, pred_y)
        # print(score , ' ' , accuracy)
        # print(accuracy)
        # print(len(self.test_data_y), type(self.test_data_y))
        # print(len(pred_y), type(pred_y))

        # Recall = TP / (TP+FN)
        # print(len(self.test_data_y), type(self.test_data_y))
        # print(len(pred_y), type(pred_y))
        # recall = recall_score(self.test_data_y, pred_y, average = 'macro')
        # print(recall)

        # Precision = TP / (TP+FP)
        # precise = precision_score(self.test_data_y, pred_y)
        # print(precise)

        # F1 = 2 (PxR/P+R)
        # f1 = f1_score(self.test_data_y, pred_y)
        # print(f1)

        # return precision/f1 score
        #return (accuracy, recall, f1, precise)

        # return score
        return (score, accuracy)
